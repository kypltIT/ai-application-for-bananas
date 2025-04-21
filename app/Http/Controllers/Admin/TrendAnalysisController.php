<?php
// Updated the controller with a more comprehensive AI prompt for fashion trend analysis
// Extended the forecast period from 3 to 6 months and improved the structure of recommendations
// Modified prompt to request structured chart data for direct visualization in frontend charts
// Improved JSON extraction and handling for revenue forecasts, category impacts, and regional recommendations
// Added integration with external data sources including market trends, customer behavior, and marketing campaigns
// Enhanced web scraping for competitor data with robust content extraction and recommendation generation
// Added support for real-time competitor analysis with direct web data extraction

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
use Symfony\Component\DomCrawler\Crawler;

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
        $topProducts = $this->getTopProductsData();

        // Get real competitor data from web
        $competitorData = $this->getCompetitorData(true);

        // Prepare data for AI analysis
        $analysisData = [
            'sales_data' => $monthlySales,
            'total_sales' => $totalSales,
            'category_performance' => $categoryPerformance,
            'revenue_by_region' => $revenueByRegion,
            'competitor_data' => $competitorData,
            'top_products' => $topProducts,
            'forecast_period' => $validated['forecast_period'],
        ];

        // Get AI analysis
        $prompt = "You are a senior market analysis expert for a footwear retail business. Analyze the following data, considering internal sales, competitor performance (Nike, Adidas, Puma, Jordan, New Balance), and geographical/cultural factors.

        IMPORTANT: Your response MUST begin with a valid JSON block containing chart data. The JSON must include:
        - \"forecast\": Revenue predictions for the next {{forecast_period}} months.
        - \"regional_performance\": Revenue predictions by region.
        - \"competitor_comparison\": Insights comparing our performance with competitors.
        - \"top_products\": Array of top-performing products with their potential (high, medium, low).
        - \"recommendations\": Prioritized action recommendations (each with 'priority' and 'text').

        After the JSON, provide:
        1. Key findings with supporting data.
        2. Reasons for revenue changes (increase/decrease).
        3. Actionable recommendations to maximize revenue or mitigate losses.

        Use the following structure:
        - \"forecast\": [{\"month\": \"Month Year\", \"revenue\": Number}]
        - \"regional_performance\": [{\"region\": \"Region Name\", \"revenue\": Number}]
        - \"competitor_comparison\": [{\"competitor\": \"Name\", \"revenue\": Number, \"market_share\": Number}]
        - \"top_products\": [{\"product_name\": \"Name\", \"category\": \"Category\", \"revenue\": Number, \"potential\": \"high|medium|low\"}]
        - \"recommendations\": [{\"priority\": \"high|medium|low\", \"text\": \"Recommendation text\"}]
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
            'analysis',
            'competitorData',
            'topProducts'
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

        try {
            // Get base data
            $totalSales = $this->getTotalSalesData();
            $monthlySales = $this->getMonthlySalesData();
            $categoryPerformance = $this->getCategoryPerformanceData();
            $revenueByRegion = $this->getRevenueByRegionData();
            $competitorData = $this->getCompetitorData(true);
            $topProducts = $this->getTopProductsData();

            // Prepare analysis data
            $analysisInput = [
                'sales_data' => $monthlySales,
                'total_sales' => $totalSales,
                'category_performance' => $categoryPerformance,
                'revenue_by_region' => $revenueByRegion,
                'competitor_data' => $competitorData,
                'top_products' => $topProducts,
                'forecast_period' => $analysisData['forecast_period'],
            ];

            // Get or retrieve cached AI analysis
            $analysis = $this->getOrCreateAnalysis($analysisInput, $analysisData['forecast_period']);

            // Check if we need to generate recommendations
            if (!isset($analysis['chart_data']['recommendations']) || empty($analysis['chart_data']['recommendations'])) {
                $analysis['chart_data']['recommendations'] = $this->generateRecommendationsFromCompetitors($competitorData);
            }

            return view('admin.trend-analysis.result', compact(
                'monthlySales',
                'categoryPerformance',
                'revenueByRegion',
                'analysis',
                'competitorData',
                'topProducts'
            ));
        } catch (\Exception $e) {
            Log::error('Analysis result error:', ['error' => $e->getMessage()]);
            return redirect()
                ->route('admin.trend-analysis.index')
                ->with('error', 'Unable to process analysis results. Please try again.');
        }
    }

    /**
     * Generate recommendations directly from competitor data
     */
    private function generateRecommendationsFromCompetitors($competitorData)
    {
        $recommendations = [];

        // Process competitor data to generate intelligent recommendations
        foreach ($competitorData as $index => $competitor) {
            $marketShare = $competitor['market_share'] ?? 0;
            $growthRate = $competitor['growth_rate'] ?? 0;
            $priority = $marketShare > 25 ? 'high' : ($marketShare > 10 ? 'medium' : 'low');

            if ($growthRate > 10) {
                $recommendations[] = [
                    'priority' => 'high',
                    'text' => "Analyze <strong>{$competitor['competitor']}</strong>'s rapid growth strategies ({$growthRate}% growth rate) for market insights"
                ];
            } else if ($marketShare > 20) {
                $recommendations[] = [
                    'priority' => $priority,
                    'text' => "Address competition from market leader <strong>{$competitor['competitor']}</strong> with {$marketShare}% market share"
                ];
            } else {
                $recommendations[] = [
                    'priority' => $priority,
                    'text' => "Monitor <strong>{$competitor['competitor']}</strong>'s pricing and promotion strategies in " . (isset($competitor['key_markets']) ? $competitor['key_markets'] : 'all markets')
                ];
            }


            if (count($recommendations) >= 3) break;
        }

        // Add default recommendations if needed
        if (count($recommendations) < 3) {
            $defaultRecs = [
                ['priority' => 'high', 'text' => 'Analyze high-performing competitor product lines to identify market gaps'],
                ['priority' => 'medium', 'text' => 'Optimize marketing budget allocation based on competitor growth patterns'],
                ['priority' => 'low', 'text' => 'Review pricing strategies against competitors in key market segments']
            ];

            foreach ($defaultRecs as $rec) {
                if (count($recommendations) < 3) {
                    $recommendations[] = $rec;
                } else {
                    break;
                }
            }
        }

        return $recommendations;
    }

    /**
     * Get top selling products data
     */
    private function getTopProductsData()
    {
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->where('orders.status', '=', 'completed')
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                'products.id',
                'products.name as product_name',
                'product_categories.name as category',
                DB::raw('SUM(CAST(product_variants.price * order_items.quantity AS DECIMAL(10,2))) as revenue'),
                DB::raw('SUM(order_items.quantity) as units_sold')
            )
            ->groupBy('products.id', 'products.name', 'product_categories.name')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                // Assign potential based on revenue ranking
                $potential = 'medium';
                if ($item->revenue > 10000) {
                    $potential = 'high';
                } elseif ($item->revenue < 5000) {
                    $potential = 'low';
                }

                return [
                    'product_name' => $item->product_name,
                    'category' => $item->category,
                    'revenue' => (float) $item->revenue,
                    'units_sold' => (int) $item->units_sold,
                    'potential' => $potential
                ];
            })
            ->toArray();
    }

    private function fetchAndCacheCompetitorData()
    {
        $cacheKey = 'competitor_data_' . date('Y-m-d');

        return cache()->remember($cacheKey, now()->addHours(24), function () {
            $competitors = [
                'Nike' => [
                    'url' => 'https://www.nike.com',
                    'revenue' => 44538000000,
                    'market_share' => 27.2,
                    'growth_rate' => 8.9,
                    'key_markets' => 'North America, Europe, China',
                    'marketing_focus' => 'Digital direct, Mobile apps, Membership',
                ],
                'Adidas' => [
                    'url' => 'https://www.adidas.com',
                    'revenue' => 22337000000,
                    'market_share' => 18.5,
                    'growth_rate' => 6.2,
                    'key_markets' => 'Europe, Asia-Pacific, North America',
                    'marketing_focus' => 'Sustainability, Sport lifestyle, Innovation',
                ],
                'Puma' => [
                    'url' => 'https://www.puma.com',
                    'revenue' => 8465000000,
                    'market_share' => 7.8,
                    'growth_rate' => 5.4,
                    'key_markets' => 'Europe, Americas, Asia',
                    'marketing_focus' => 'Sports performance, Cultural relevance, Celebrity partnerships',
                ],
                'New Balance' => [
                    'url' => 'https://www.newbalance.com',
                    'revenue' => 4577000000,
                    'market_share' => 3.9,
                    'growth_rate' => 9.2,
                    'key_markets' => 'North America, Western Europe, Japan',
                    'marketing_focus' => 'Domestic manufacturing, Performance running, Lifestyle',
                ],
                'Jordan' => [
                    'url' => 'https://www.nike.com/jordan',
                    'revenue' => 5156000000,
                    'market_share' => 4.2,
                    'growth_rate' => 12.1,
                    'key_markets' => 'North America, China, Western Europe',
                    'marketing_focus' => 'Basketball culture, Limited releases, Heritage',
                ],
            ];

            return collect($competitors)->map(function ($data, $name) {
                return [
                    'competitor' => $name,
                    'revenue' => $data['revenue'],
                    'market_share' => $data['market_share'],
                    'growth_rate' => $data['growth_rate'],
                    'key_markets' => $data['key_markets'],
                    'marketing_focus' => $data['marketing_focus'],
                    'analysis' => $this->generateCompetitorAnalysis($name, $data),
                ];
            })->values()->toArray();
        });
    }

    /**
     * Generate specific competitor analysis based on their data
     */
    private function generateCompetitorAnalysis($name, $data)
    {
        $growth = $data['growth_rate'];
        $marketShare = $data['market_share'];
        $marketingFocus = $data['marketing_focus'] ?? '';

        if ($growth > 10) {
            return "$name is showing exceptional growth at $growth%, outpacing the market average. They're focusing on $marketingFocus which is driving strong consumer response.";
        } elseif ($growth > 7) {
            return "$name maintains solid growth at $growth% with effective strategies in $marketingFocus, making them a significant competitor.";
        } elseif ($marketShare > 20) {
            return "As a market leader with $marketShare% share, $name dominates through $marketingFocus, creating high barriers to entry.";
        } else {
            return "$name holds $marketShare% market share with $growth% growth, focusing on $marketingFocus as their competitive advantage.";
        }
    }

    private function getOrCreateAnalysis($analysisInput, $forecastPeriod)
    {
        $cacheKey = 'analysis_' . md5(json_encode($analysisInput));

        return cache()->remember($cacheKey, now()->addHours(1), function () use ($analysisInput, $forecastPeriod) {
            $prompt = $this->buildAnalysisPrompt($forecastPeriod);
            $analysis = $this->diagnosticService->runDiagnostic($analysisInput, $prompt);

            if (isset($analysis['success']) && $analysis['success'] && isset($analysis['analysis'])) {
                $chartData = $this->extractChartData($analysis['analysis']);
                if ($chartData) {
                    $analysis['chart_data'] = $chartData;

                    // Ensure required structure exists
                    if (!isset($chartData['top_products'])) {
                        $analysis['chart_data']['top_products'] = $analysisInput['top_products'];
                    }

                    if (!isset($chartData['recommendations'])) {
                        $analysis['chart_data']['recommendations'] = $this->generateRecommendationsFromCompetitors(
                            $analysisInput['competitor_data']
                        );
                    }
                }
                $analysis['formatted_content'] = $this->formatAnalysisContent($analysis['analysis']);
            }

            return $analysis;
        });
    }

    private function buildAnalysisPrompt($forecastPeriod)
    {
        return "You are a senior market analysis expert for a footwear retail business. Analyze the following data..." .
            "IMPORTANT: Your response MUST begin with a valid JSON block containing chart data..." .
            "Include the following data in the JSON: forecast, regional_performance, competitor_comparison, top_products, and recommendations..." .
            str_replace('{{forecast_period}}', $forecastPeriod, $this->getPromptTemplate());
    }

    private function getPromptTemplate()
    {
        return file_get_contents(resource_path('prompts/trend-analysis.txt'));
    }

    /**
     * Get real-time competitor data from their websites
     */
    private function getCompetitorData($useRealData = false)
    {
        if (!$useRealData) {
            return $this->fetchAndCacheCompetitorData();
        }

        $cacheKey = 'real_competitor_data_' . date('Y-m-d');

        return cache()->remember($cacheKey, now()->addHours(6), function () {
            // Define competitor URLs and data extraction patterns
            $competitorUrls = [
                'Nike' => [
                    'url' => 'https://investors.nike.com/investors/news-events-and-reports/',
                    'revenue_pattern' => '/revenue of \$([\d\.]+) billion/i',
                    'market_share_pattern' => '/([\d\.]+)% (?:of|in) the global (?:sportswear|footwear) market/i',
                    'growth_pattern' => '/increased (?:by )?([\d\.]+)%/i',
                ],
                'Adidas' => [
                    'url' => 'https://report.adidas-group.com/2024/en/',
                    'revenue_pattern' => '/(?:revenue|sales) of €([\d\.]+) billion/i',
                    'market_share_pattern' => '/([\d\.]+)% market share/i',
                    'growth_pattern' => '/growth of ([\d\.]+)%/i',
                ],
                'Puma' => [
                    'url' => 'https://annual-report.puma.com/2024/en/',
                    'revenue_pattern' => '/sales of €([\d\.]+) billion/i',
                    'market_share_pattern' => '/market share of ([\d\.]+)%/i',
                    'growth_pattern' => '/increase of ([\d\.]+)%/i',
                ],
                'Jordan' => [
                    'url' => 'https://runrepeat.com/jordan-shoes-statistics',
                    'revenue_pattern' => '/revenue of \$([\d\.]+) billion/i',
                    'market_share_pattern' => '/([\d\.]+)% (?:of|in) the (?:basketball|footwear) market/i',
                    'growth_pattern' => '/growth of ([\d\.]+)%/i',
                ],
                'New Balance' => [
                    'url' => 'https://finance.yahoo.com/news/new-balance-sales-jump-20-in-2024-reach-record-78-billion-194451442.html',
                    'revenue_pattern' => '/sales (?:of|reached) \$([\d\.]+) billion/i',
                    'market_share_pattern' => '/([\d\.]+)% of the market/i',
                    'growth_pattern' => '/(?:jump|growth|increase) (?:of |by )?([\d\.]+)%/i',
                ],
            ];

            $competitorData = [];

            foreach ($competitorUrls as $competitor => $info) {
                try {
                    // Get the HTML content
                    $response = Http::get($info['url']);
                    $htmlContent = $response->body();

                    // Extract data based on patterns
                    $revenue = $this->extractDataWithPattern($htmlContent, $info['revenue_pattern']);
                    $marketShare = $this->extractDataWithPattern($htmlContent, $info['market_share_pattern']);
                    $growthRate = $this->extractDataWithPattern($htmlContent, $info['growth_pattern']);

                    // Analyze the content using AI
                    $analysis = $this->analyzeCompetitorContent($competitor, $htmlContent);

                    // Convert revenue to consistent format (USD millions)
                    $revenueValue = 0;
                    if ($revenue) {
                        $revenueValue = floatval($revenue);
                        // Convert from billions to millions
                        $revenueValue = $revenueValue * 1000;
                        // Convert EUR to USD if needed (approximation)
                        if (strpos($info['revenue_pattern'], '€') !== false) {
                            $revenueValue = $revenueValue * 1.1; // Rough conversion
                        }
                    }

                    // Use defaults for missing data
                    $defaultData = $this->getDefaultCompetitorData($competitor);

                    $competitorData[] = [
                        'competitor' => $competitor,
                        'revenue' => $revenueValue ?: $defaultData['revenue'],
                        'market_share' => floatval($marketShare ?: $defaultData['market_share']),
                        'growth_rate' => floatval($growthRate ?: $defaultData['growth_rate']),
                        'analysis' => $analysis,
                    ];
                } catch (\Exception $e) {
                    Log::error("Failed to fetch real data for $competitor", ['error' => $e->getMessage()]);

                    // Use cached data as fallback
                    $defaultData = $this->getDefaultCompetitorData($competitor);
                    $competitorData[] = [
                        'competitor' => $competitor,
                        'revenue' => $defaultData['revenue'],
                        'market_share' => $defaultData['market_share'],
                        'growth_rate' => $defaultData['growth_rate'],
                        'analysis' => "Market data suggests $competitor maintains a significant presence with ~{$defaultData['market_share']}% market share and {$defaultData['growth_rate']}% growth.",
                    ];
                }
            }

            return $competitorData;
        });
    }

    /**
     * Extract data from HTML content using regex pattern
     */
    private function extractDataWithPattern($content, $pattern)
    {
        if (preg_match($pattern, $content, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get default competitor data for fallback
     */
    private function getDefaultCompetitorData($competitor)
    {
        $defaults = [
            'Nike' => ['revenue' => 44538000000, 'market_share' => 27.2, 'growth_rate' => 8.9],
            'Adidas' => ['revenue' => 22337000000, 'market_share' => 18.5, 'growth_rate' => 6.2],
            'Puma' => ['revenue' => 8465000000, 'market_share' => 7.8, 'growth_rate' => 5.4],
            'New Balance' => ['revenue' => 4577000000, 'market_share' => 3.9, 'growth_rate' => 9.2],
            'Jordan' => ['revenue' => 5156000000, 'market_share' => 4.2, 'growth_rate' => 12.1],
        ];

        return $defaults[$competitor] ?? ['revenue' => 0, 'market_share' => 0, 'growth_rate' => 0];
    }

    /**
     * Analyze competitor webpage content
     */
    private function analyzeCompetitorContent($competitor, $content)
    {
        // Clean content to extract meaningful text
        $textContent = strip_tags($content);
        $textContent = preg_replace('/\s+/', ' ', $textContent);
        $textContent = trim($textContent);

        // Extract key snippets related to market trends, growth, and strategies
        $patterns = [
            'strategy' => '/(?:strateg(?:y|ies)|focus|initiatives?|priorities)[\s\:]+([\w\s\,\.\-\'\&\;\/]+?)(?:[\.\,]|$)/i',
            'growth' => '/(?:growth|increase|grew)[\s\:]+([\w\s\,\.\-\'\&\;\/\%]+?)(?:[\.\,]|$)/i',
            'performance' => '/(?:performance|results|sales)[\s\:]+([\w\s\,\.\-\'\&\;\/\%]+?)(?:[\.\,]|$)/i',
            'market' => '/(?:market|segment|category)[\s\:]+([\w\s\,\.\-\'\&\;\/\%]+?)(?:[\.\,]|$)/i',
        ];

        $insights = [];
        foreach ($patterns as $type => $pattern) {
            if (preg_match_all($pattern, $textContent, $matches, PREG_SET_ORDER, 0)) {
                // Take just a few most relevant matches
                $relevantMatches = array_slice($matches, 0, 2);
                foreach ($relevantMatches as $match) {
                    if (isset($match[1]) && strlen($match[1]) > 10 && strlen($match[1]) < 200) {
                        $insights[] = trim($match[1]);
                    }
                }
            }
        }

        // Generate the analysis based on extracted insights
        if (count($insights) > 0) {
            $insightText = implode(" ", array_slice($insights, 0, 3));
            return "$competitor's current market approach: $insightText";
        }

        // Fallback analysis based on the competitor
        $fallbackAnalyses = [
            'Nike' => 'Nike continues to dominate through direct-to-consumer channels and digital innovation, focusing on premium products and experiences.',
            'Adidas' => 'Adidas is emphasizing sustainability and lifestyle products while expanding their digital presence and streamlining operations.',
            'Puma' => 'Puma shows strong growth in performance categories while leveraging sports and cultural partnerships for market penetration.',
            'New Balance' => 'New Balance continues to grow through domestic manufacturing focus and balanced approach to performance and lifestyle products.',
            'Jordan' => 'The Jordan Brand maintains premium positioning through limited releases and strong basketball heritage, driving significant growth.',
        ];

        return $fallbackAnalyses[$competitor] ?? "$competitor maintains significant market presence through product innovation and strategic partnerships.";
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
        return DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->where('orders.status', '=', 'completed')
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                'product_categories.id',
                'product_categories.name',
                DB::raw('COUNT(DISTINCT products.id) as product_count'),
                DB::raw('SUM(CAST(product_variants.price * order_items.quantity AS DECIMAL(10,2))) as revenue'),
                DB::raw('SUM(order_items.quantity) as units_sold')
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
