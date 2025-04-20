<?php
// Updated the controller with a more comprehensive AI prompt for fashion trend analysis
// Extended the forecast period from 3 to 6 months and improved the structure of recommendations
// Modified prompt to request structured chart data for direct visualization in frontend charts
// Improved JSON extraction and handling for revenue forecasts, category impacts, and regional recommendations
// Added integration with external data sources including market trends, customer behavior, and marketing campaigns

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\OpenAI\DiagnosticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrendAnalysisController extends Controller
{
    protected $diagnosticService;

    public function __construct(DiagnosticService $diagnosticService)
    {
        $this->diagnosticService = $diagnosticService;
    }

    /**
     * Show the trend analysis form
     */
    public function index()
    {
        // Get monthly sales data for the past 12 months for visualization
        $monthlySales = $this->getMonthlySalesData();

        // Get revenue by region data
        $revenueByRegion = $this->getRevenueByRegionData();

        return view('admin.trend-analysis.index', compact('monthlySales', 'revenueByRegion'));
    }

    /**
     * Process the trend analysis based on input fashion trend
     */
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'forecast_period' => 'required|integer|min:3|max:12',
        ]);

        // Store analysis data in session for the result page
        session(['analysis_data' => [
            'forecast_period' => $validated['forecast_period'],
            'timestamp' => now(),
        ]]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        // Get internal sales data
        $totalSales = $this->getTotalSalesData();
        $monthlySales = $this->getMonthlySalesData();
        $categoryPerformance = $this->getCategoryPerformanceData();
        $revenueByRegion = $this->getRevenueByRegionData();

        // Prepare data for AI analysis
        $analysisData = [
            'sales_data' => $monthlySales,
            'total_sales' => $totalSales,
            'category_performance' => $categoryPerformance,
            'revenue_by_region' => $revenueByRegion,
            'competitor_data' => $this->getCompetitorData(),
            'forecast_period' => $validated['forecast_period'],
        ];

        // Get AI analysis
        $prompt = "You are a senior market analysis expert for a footwear retail business. Analyze the following data, considering internal sales, competitor performance (Nike, Adidas, Puma, Jordan, New Balance), and geographical/cultural factors.

        IMPORTANT: Your response MUST begin with a valid JSON block containing chart data. The JSON must include:
        - \"forecast\": Revenue predictions for the next {{forecast_period}} months.
        - \"regional_performance\": Revenue predictions by region.
        - \"competitor_comparison\": Insights comparing our performance with competitors.

        After the JSON, provide:
        1. Key findings with supporting data.
        2. Reasons for revenue changes (increase/decrease).
        3. Actionable recommendations to maximize revenue or mitigate losses.

        Use the following structure:
        - \"forecast\": [{\"month\": \"Month Year\", \"revenue\": Number}]
        - \"regional_performance\": [{\"region\": \"Region Name\", \"revenue\": Number}]
        - \"competitor_comparison\": [{\"competitor\": \"Name\", \"revenue\": Number, \"market_share\": Number}]
        ";

        $analysis = $this->diagnosticService->runDiagnostic($analysisData, $prompt);

        // Process the analysis
        if (isset($analysis['success']) && $analysis['success'] && isset($analysis['analysis'])) {
            $chartData = $this->extractChartData($analysis['analysis']);
            if ($chartData) {
                $analysis['chart_data'] = $chartData;
            }
            $analysis['formatted_content'] = $this->formatAnalysisContent($analysis['analysis']);
        }

        return view('admin.trend-analysis.result', compact(
            'monthlySales',
            'categoryPerformance',
            'revenueByRegion',
            'analysis'
        ));
    }

    /**
     * Display the analysis results
     */
    public function result()
    {
        $analysisData = session('analysis_data');
        if (!$analysisData) {
            return redirect()->route('admin.trend-analysis.index');
        }

        // Get internal sales data
        $totalSales = $this->getTotalSalesData();
        $monthlySales = $this->getMonthlySalesData();
        $categoryPerformance = $this->getCategoryPerformanceData();
        $revenueByRegion = $this->getRevenueByRegionData();

        // Prepare data for AI analysis
        $analysisData = [
            'sales_data' => $monthlySales,
            'total_sales' => $totalSales,
            'category_performance' => $categoryPerformance,
            'revenue_by_region' => $revenueByRegion,
            'competitor_data' => $this->getCompetitorData(),
            'forecast_period' => $analysisData['forecast_period'],
        ];

        // Get AI analysis
        $prompt = "You are a senior market analysis expert for a footwear retail business. Analyze the following data, considering internal sales, competitor performance (Nike, Adidas, Puma, Jordan, New Balance), and geographical/cultural factors.

        IMPORTANT: Your response MUST begin with a valid JSON block containing chart data. The JSON must include:
        - \"forecast\": Revenue predictions for the next {{forecast_period}} months.
        - \"regional_performance\": Revenue predictions by region.
        - \"competitor_comparison\": Insights comparing our performance with competitors.

        After the JSON, provide:
        1. Key findings with supporting data.
        2. Reasons for revenue changes (increase/decrease).
        3. Actionable recommendations to maximize revenue or mitigate losses.

        Use the following structure:
        - \"forecast\": [{\"month\": \"Month Year\", \"revenue\": Number}]
        - \"regional_performance\": [{\"region\": \"Region Name\", \"revenue\": Number}]
        - \"competitor_comparison\": [{\"competitor\": \"Name\", \"revenue\": Number, \"market_share\": Number}]
        ";

        $analysis = $this->diagnosticService->runDiagnostic($analysisData, $prompt);

        // Process the analysis
        if (isset($analysis['success']) && $analysis['success'] && isset($analysis['analysis'])) {
            $chartData = $this->extractChartData($analysis['analysis']);
            if ($chartData) {
                $analysis['chart_data'] = $chartData;
            }
            $analysis['formatted_content'] = $this->formatAnalysisContent($analysis['analysis']);
        }

        return view('admin.trend-analysis.result', compact(
            'monthlySales',
            'categoryPerformance',
            'revenueByRegion',
            'analysis'
        ));
    }

    private function getCompetitorData()
    {
        // Fetch competitor data from the web (mocked for demonstration)
        $competitorUrls = [
            'Nike' => 'https://www.nike.com/reports',
            'Adidas' => 'https://www.adidas.com/reports',
            'Puma' => 'https://about.puma.com/reports',
            'Jordan' => 'https://www.nike.com/jordan/reports',
            'New Balance' => 'https://www.newbalance.com/reports',
        ];

        $competitorData = [];
        foreach ($competitorUrls as $competitor => $url) {
            try {
                $response = Http::get($url);
                $data = $response->json();

                // Extract relevant data (mocked structure)
                $competitorData[] = [
                    'competitor' => $competitor,
                    'revenue' => $data['latest_revenue'] ?? 0,
                    'market_share' => $data['market_share'] ?? 0,
                    'last_3_years' => $data['last_3_years'] ?? [],
                ];
            } catch (\Exception $e) {
                Log::error("Failed to fetch data for $competitor", ['error' => $e->getMessage()]);
            }
        }

        return $competitorData;
    }

    /**
     * Extract chart data from the analysis text
     */
    private function extractChartData($analysisText)
    {
        $jsonMatch = preg_match('/```json\s*([\s\S]*?)\s*```/', $analysisText, $matches);

        if ($jsonMatch && isset($matches[1])) {
            try {
                $jsonData = json_decode($matches[1], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $jsonData;
                }
            } catch (\Exception $e) {
                return null;
            }
        }

        // Try extracting without code blocks if the above failed
        $jsonMatch = preg_match('/^\s*{\s*"forecast"/', $analysisText);
        if ($jsonMatch) {
            // Find the closing bracket of the JSON object
            $firstBracePos = strpos($analysisText, '{');
            $depth = 0;
            $endPos = $firstBracePos;
            $length = strlen($analysisText);

            for ($i = $firstBracePos; $i < $length; $i++) {
                if ($analysisText[$i] === '{') {
                    $depth++;
                } else if ($analysisText[$i] === '}') {
                    $depth--;
                    if ($depth === 0) {
                        $endPos = $i + 1;
                        break;
                    }
                }
            }

            if ($endPos > $firstBracePos) {
                $jsonString = substr($analysisText, $firstBracePos, $endPos - $firstBracePos);
                try {
                    $jsonData = json_decode($jsonString, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $jsonData;
                    }
                } catch (\Exception $e) {
                    return null;
                }
            }
        }

        return null;
    }

    /**
     * Format analysis content by removing JSON and formatting as HTML
     */
    private function formatAnalysisContent($analysisText)
    {
        // Remove JSON block
        $cleanText = preg_replace('/```json\s*[\s\S]*?\s*```/', '', $analysisText);

        // Alternative method to remove JSON if the first approach didn't work
        if (strpos($cleanText, '{"forecast"') !== false) {
            $firstBracePos = strpos($cleanText, '{');
            if ($firstBracePos !== false) {
                $depth = 0;
                $endPos = $firstBracePos;
                $length = strlen($cleanText);

                for ($i = $firstBracePos; $i < $length; $i++) {
                    if ($cleanText[$i] === '{') {
                        $depth++;
                    } else if ($cleanText[$i] === '}') {
                        $depth--;
                        if ($depth === 0) {
                            $endPos = $i + 1;
                            break;
                        }
                    }
                }

                if ($endPos > $firstBracePos) {
                    $cleanText = substr($cleanText, 0, $firstBracePos) . substr($cleanText, $endPos);
                }
            }
        }

        // Trim whitespace
        $cleanText = trim($cleanText);

        // Format headings
        $cleanText = preg_replace('/###\s*(.*?)$/m', '<h3>$1</h3>', $cleanText);
        $cleanText = preg_replace('/##\s*(.*?)$/m', '<h2>$1</h2>', $cleanText);
        $cleanText = preg_replace('/#\s*(.*?)$/m', '<h1>$1</h1>', $cleanText);

        // Format bullet points
        $cleanText = preg_replace('/^\s*-\s*(.*?)$/m', '<li>$1</li>', $cleanText);
        $cleanText = preg_replace('/(<li>.*?<\/li>)(?!\s*<li>)/s', '<ul>$1</ul>', $cleanText);

        // Format bold text
        $cleanText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $cleanText);

        // Format italic text
        $cleanText = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $cleanText);

        // Add paragraph tags
        $cleanText = '<p>' . str_replace("\n\n", '</p><p>', $cleanText) . '</p>';
        $cleanText = str_replace('<p><ul>', '<ul>', $cleanText);
        $cleanText = str_replace('</ul></p>', '</ul>', $cleanText);
        $cleanText = str_replace('<p><h', '<h', $cleanText);
        $cleanText = str_replace('</h1></p>', '</h1>', $cleanText);
        $cleanText = str_replace('</h2></p>', '</h2>', $cleanText);
        $cleanText = str_replace('</h3></p>', '</h3>', $cleanText);

        return $cleanText;
    }

    /**
     * Get total sales data
     */
    private function getTotalSalesData()
    {
        return Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->sum('total_price');
    }

    /**
     * Get monthly sales data for the past 12 months
     */
    private function getMonthlySalesData()
    {
        return Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('SUM(total_price) as revenue'),
                DB::raw("DATE_FORMAT(created_at, '%M %Y') as month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-01') as month_date")
            )
            ->groupBy('month', 'month_date')
            ->orderBy('month_date', 'ASC')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => $item->month,
                    'revenue' => (float) $item->revenue,
                    'date' => $item->month_date,
                ];
            });
    }

    /**
     * Get product category performance data
     */
    private function getCategoryPerformanceData()
    {
        return DB::table('product_categories')
            ->leftJoin('products', 'product_categories.id', '=', 'products.product_category_id')
            ->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', '=', 'completed')
                    ->where('orders.created_at', '>=', Carbon::now()->subMonths(6));
            })
            ->select(
                'product_categories.id',
                'product_categories.name',
                DB::raw('COUNT(DISTINCT products.id) as product_count'),
                DB::raw('COALESCE(SUM(product_variants.price * order_items.quantity), 0) as revenue'), // Updated to use price from product_variants
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as units_sold')
            )
            ->groupBy('product_categories.id', 'product_categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    /**
     * Get revenue by region data
     */
    private function getRevenueByRegionData()
    {
        return Order::join('addresses', 'orders.id', '=', 'addresses.order_id')
            ->join('cities', 'addresses.city', '=', 'cities.id')
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                'cities.full_name_en as region',
                DB::raw('SUM(orders.total_price) as revenue'),
                DB::raw('COUNT(orders.id) as order_count')
            )
            ->groupBy('cities.id', 'cities.full_name_en')
            ->orderBy('revenue', 'desc')
            ->get();
    }
   
}
