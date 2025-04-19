<?php
// Updated the controller with a more comprehensive AI prompt for fashion trend analysis
// Extended the forecast period from 3 to 6 months and improved the structure of recommendations
// Modified prompt to request structured chart data for direct visualization in frontend charts
// Improved JSON extraction and handling for revenue forecasts, category impacts, and regional recommendations

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\OpenAI\DiagnosticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Get product category performance data
        $categoryPerformance = $this->getCategoryPerformanceData();

        return view('admin.trend-analysis.index', compact('monthlySales', 'categoryPerformance'));
    }

    /**
     * Process the trend analysis based on input fashion trend
     */
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'forecast_period' => 'required|integer|min:3|max:12',
        ]);

        // Get sales data
        $monthlySales = $this->getMonthlySalesData();
        $categoryPerformance = $this->getCategoryPerformanceData();
        $revenueByRegion = $this->getRevenueByRegionData();

        // Prepare data for AI analysis
        $analysisData = [
            'sales_data' => $monthlySales,
            'category_performance' => $categoryPerformance,
            'revenue_by_region' => $revenueByRegion,
            'forecast_period' => $validated['forecast_period'],
        ];

        // Get AI analysis
        $prompt = "You are a senior market analysis expert for a fashion retail business with deep knowledge of retail operations and inventory management. Analyze the following sales data in relation to the described fashion trend.

        IMPORTANT: Your response MUST begin with a valid JSON block containing chart data. Don't include any introductory text before the JSON. The JSON must be formatted exactly as in the following example, with no alterations to the structure:

        ```json
        {
          \"forecast\": [
            {\"month\": \"July 2023\", \"revenue\": 125000},
            {\"month\": \"August 2023\", \"revenue\": 130000},
            {\"month\": \"September 2023\", \"revenue\": 128000},
            {\"month\": \"October 2023\", \"revenue\": 135000},
            {\"month\": \"November 2023\", \"revenue\": 142000},
            {\"month\": \"December 2023\", \"revenue\": 150000}
          ],
          \"category_impact\": [
            {\"category\": \"Basas\", \"growth_percentage\": 15, \"impact\": \"positive\"},
            {\"category\": \"Vintas\", \"growth_percentage\": -5, \"impact\": \"negative\"},
            {\"category\": \"Urbas\", \"growth_percentage\": 10, \"impact\": \"positive\"},
            {\"category\": \"Pattas\", \"growth_percentage\": 0, \"impact\": \"neutral\"}
          ],
          \"recommended_regions\": [\"New York\", \"Los Angeles\", \"Miami\"]
        }
        ```

        The JSON data MUST contain the following properties:
        - \"forecast\": An array of {{forecast_period}} objects with month and revenue predictions for the next {{forecast_period}} months.
        - \"category_impact\": An array of objects for each product category, with growth_percentage and impact assessment.
        - \"recommended_regions\": An array of strings with recommended regions to focus on.

        Use the actual category names from the provided data. Calculate realistic growth percentages based on the trend.

        Task: Identify potential best-selling products and categories for the near future, explaining the reasons behind their potential success. Additionally, provide strategic recommendations to mitigate revenue declines by adjusting inventory, shifting products, and leveraging promotional strategies.

        Instructions:

        Identify Trending Products and Categories: Analyze current market trends and consumer behaviors to predict products and categories that could become best-sellers in the near future. Consider emerging trends like sustainability, health, wellness, new market trends, and AI-integrated devices,...

        Provide Reasons for Their Success: Explain the factors driving demand for each identified category or product. Consider consumer interest shifts, technological advancements, and market gaps that are being addressed.

        Strategic Recommendations to Reduce Revenue Decline:

        Suggest strategies for mitigating revenue drops:

        - Reduce production or imports for slow-moving products.

        - Shift inventory from less popular areas to higher-performing ones.

        - Implement cross-selling and bundling strategies to clear stagnant stock.

        - Utilize promotional tactics like discounts, limited-time offers, and exclusive deals to boost sales of underperforming products.

        - Suggest tracking market trends and customer feedback to stay agile and make data-driven adjustments.

        Output Format: A list of identified best-selling products and categories with reasons for their potential success. A set of actionable recommendations for handling slow-moving products, inventory adjustments, and promotional strategies to prevent revenue drops. Format your response with clear section headings and bullet points for easy reading. Prioritize specific, measurable recommendations over general advice.";

        $analysis = $this->diagnosticService->runDiagnostic($analysisData, $prompt);

        // Process the analysis
        if (isset($analysis['success']) && $analysis['success'] && isset($analysis['analysis'])) {
            // Extract chart data for JavaScript
            $chartData = $this->extractChartData($analysis['analysis']);
            if ($chartData) {
                $analysis['chart_data'] = $chartData;
            }

            // Remove JSON and format HTML content for display
            $analysis['formatted_content'] = $this->formatAnalysisContent($analysis['analysis']);
        }

        // Return the view with all data
        return view('admin.trend-analysis.result', compact(
            'monthlySales',
            'categoryPerformance',
            'revenueByRegion',
            'analysis'
        ));
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
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(6))
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
