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

        // Get internal sales data
        $monthlySales = $this->getMonthlySalesData();
        $categoryPerformance = $this->getCategoryPerformanceData();
        $revenueByRegion = $this->getRevenueByRegionData();

        // Get external data
        $marketTrends = $this->getMarketTrendsData();
        $customerBehavior = $this->getCustomerBehaviorData();
        $marketingCampaigns = $this->getMarketingCampaignsData();

        // Prepare data for AI analysis
        $analysisData = [
            'sales_data' => $monthlySales,
            'category_performance' => $categoryPerformance,
            'revenue_by_region' => $revenueByRegion,
            'market_trends' => $marketTrends,
            'customer_behavior' => $customerBehavior,
            'marketing_campaigns' => $marketingCampaigns,
            'forecast_period' => $validated['forecast_period'],
        ];

        // Get AI analysis
        $prompt = "You are a senior market analysis expert for a fashion retail business with deep knowledge of retail operations and inventory management. Analyze the following sales data in relation to the described fashion trend, incorporating insights from external data sources.

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
            {\"category\": \"Dresses\", \"growth_percentage\": 15, \"impact\": \"positive\"},
            {\"category\": \"Shirts\", \"growth_percentage\": -5, \"impact\": \"negative\"},
            {\"category\": \"Accessories\", \"growth_percentage\": 10, \"impact\": \"positive\"},
            {\"category\": \"Pants\", \"growth_percentage\": 0, \"impact\": \"neutral\"}
          ],
          \"recommended_regions\": [\"New York\", \"Los Angeles\", \"Miami\"],
          \"external_factors\": {
            \"market_trends\": [\"sustainable_fashion\", \"digital_nomad_style\"],
            \"customer_preferences\": [\"eco_friendly\", \"comfort_first\"],
            \"campaign_impact\": [\"summer_sale\", \"back_to_school\"]
          }
        }
        ```

        The JSON data MUST contain the following properties:
        - \"forecast\": An array of {{forecast_period}} objects with month and revenue predictions for the next {{forecast_period}} months.
        - \"category_impact\": An array of objects for each product category, with growth_percentage and impact assessment.
        - \"recommended_regions\": An array of strings with recommended regions to focus on.
        - \"external_factors\": An object containing arrays of relevant market trends, customer preferences, and campaign impacts.

        Use the actual category names from the provided data. Calculate realistic growth percentages based on the trend and external data.

        Task: Identify potential best-selling products and categories for the near future, explaining the reasons behind their potential success. Additionally, provide strategic recommendations to mitigate revenue declines by adjusting inventory, shifting products, and leveraging promotional strategies.

        Instructions:

        Identify Trending Products and Categories: Analyze current market trends and consumer behaviors to predict products and categories that could become best-sellers in the near future. Consider:
        - Emerging market trends from external sources
        - Customer behavior patterns and preferences
        - Impact of current and planned marketing campaigns
        - Industry reports and fashion forecasts
        - Social media trends and influencer impact

        Provide Reasons for Their Success: Explain the factors driving demand for each identified category or product. Consider:
        - Consumer interest shifts from external data
        - Technological advancements and market gaps
        - Marketing campaign effectiveness
        - Regional preferences and cultural influences
        - Seasonal and event-based opportunities

        Strategic Recommendations to Reduce Revenue Decline:

        Suggest strategies for mitigating revenue drops:

        - Align inventory with market trends and customer preferences
        - Optimize marketing campaigns based on customer behavior data
        - Implement targeted promotions for underperforming categories
        - Adjust product mix based on external market insights
        - Leverage successful campaign elements across regions
        - Monitor and respond to emerging market trends

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
            'marketTrends',
            'customerBehavior',
            'marketingCampaigns',
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

    /**
     * Get market trends data from external sources
     */
    private function getMarketTrendsData()
    {
        // Example implementation - replace with actual API calls
        return [
            'sustainable_fashion' => [
                'trend_score' => 0.85,
                'growth_rate' => 0.25,
                'source' => 'Fashion Industry Report 2023'
            ],
            'digital_nomad_style' => [
                'trend_score' => 0.72,
                'growth_rate' => 0.18,
                'source' => 'Social Media Analysis'
            ],
            'minimalist_design' => [
                'trend_score' => 0.68,
                'growth_rate' => 0.15,
                'source' => 'Consumer Survey'
            ]
        ];
    }

    /**
     * Get customer behavior data from analytics
     */
    private function getCustomerBehaviorData()
    {
        // Example implementation - replace with actual analytics data
        return [
            'preferences' => [
                'eco_friendly' => 0.78,
                'comfort_first' => 0.82,
                'price_sensitive' => 0.65
            ],
            'purchase_patterns' => [
                'online_vs_store' => ['online' => 0.65, 'store' => 0.35],
                'seasonal_peaks' => ['summer' => 0.45, 'winter' => 0.35],
                'average_basket_size' => 2.5
            ],
            'demographics' => [
                'age_groups' => ['18-24' => 0.25, '25-34' => 0.35, '35-44' => 0.25],
                'gender_distribution' => ['female' => 0.65, 'male' => 0.35]
            ]
        ];
    }

    /**
     * Get marketing campaigns data
     */
    private function getMarketingCampaignsData()
    {
        // Example implementation - replace with actual campaign data
        return [
            'active_campaigns' => [
                'summer_sale' => [
                    'start_date' => '2023-06-01',
                    'end_date' => '2023-08-31',
                    'discount_rate' => 0.20,
                    'target_categories' => ['summer_dresses', 'swimwear']
                ],
                'back_to_school' => [
                    'start_date' => '2023-07-15',
                    'end_date' => '2023-09-15',
                    'discount_rate' => 0.15,
                    'target_categories' => ['casual_wear', 'accessories']
                ]
            ],
            'campaign_performance' => [
                'summer_sale' => [
                    'conversion_rate' => 0.12,
                    'average_order_value' => 500000,
                    'customer_acquisition' => 1200
                ],
                'back_to_school' => [
                    'conversion_rate' => 0.08,
                    'average_order_value' => 500000,
                    'customer_acquisition' => 800
                ]
            ]
        ];
    }
}
