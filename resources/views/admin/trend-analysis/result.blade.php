@extends('layouts.admin.app')

@section('title', 'Market Trend Analysis Results')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Market Trend Analysis Results</h1>
                    <div class="btn-group">
                        <a href="{{ route('admin.trend-analysis.index') }}"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Analysis
                        </a>
                        <button id="exportBtn" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                            <i class="fas fa-download fa-sm text-white-50"></i> Export Report
                        </button>
                    </div>
                </div>

                @if (isset($analysis) && $analysis['success'])
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">AI Market Analysis</h6>
                                    <div class="dropdown no-arrow">
                                        <span class="text-xs text-gray-500">Powered by
                                            {{ $analysis['model'] ?? 'AI' }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="ai-analysis-content">
                                        {!! $analysis['formatted_content'] ?? nl2br(e($analysis['analysis'])) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Revenue Trend and Forecast</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area" style="position: relative;">
                                    <div id="revenueChartLoader" class="chart-loader">Loading data...</div>
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Regional Performance</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar" style="position: relative;">
                                    <div id="regionBarChartLoader" class="chart-loader">Loading data...</div>
                                    <canvas id="regionBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Category Performance</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie" style="position: relative;">
                                    <div id="categoryPieChartLoader" class="chart-loader">Loading data...</div>
                                    <canvas id="categoryPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Recommendations</h6>
                            </div>
                            <div class="card-body">
                                <div id="recommendations-content" class="recommendations-content">
                                    <div id="recommendationsLoader" class="chart-loader">Loading recommendations...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide all loaders by default (they will show during actual loading)
            document.querySelectorAll('.chart-loader').forEach(loader => {
                loader.style.display = 'none';
            });

            // Cache for chart data and configurations
            const chartCache = {
                colors: {},
                configs: {}
            };

            // Color configuration constants for better maintainability
            const COLORS = {
                // Pride flag colors
                red: {
                    base: '#E40303',
                    hover: '#BE0202'
                },
                orange: {
                    base: '#FF8C00',
                    hover: '#E67E00'
                },
                yellow: {
                    base: '#FFED00',
                    hover: '#E5D500'
                },
                green: {
                    base: '#008026',
                    hover: '#006B20'
                },
                blue: {
                    base: '#004DFF',
                    hover: '#0040D9'
                },
                indigo: {
                    base: '#750787',
                    hover: '#5D0669'
                },
                violet: {
                    base: '#9C27B0',
                    hover: '#7B1FA2'
                },
                // Keep some utility colors
                positive: {
                    base: '#008026', // Using green from pride flag
                    hover: '#006B20'
                },
                negative: {
                    base: '#E40303', // Using red from pride flag
                    hover: '#BE0202'
                },
                neutral: {
                    base: '#FFED00', // Using yellow from pride flag
                    hover: '#E5D500'
                }
            };

            // Set up color palette for reuse with pride flag colors
            const colorPalette = [
                COLORS.red,
                COLORS.orange,
                COLORS.yellow,
                COLORS.green,
                COLORS.blue,
                COLORS.indigo,
                COLORS.violet
            ];

            // Formatter function for currency
            const formatCurrency = (value) => {
                return value.toLocaleString() + ' VND';
            };

            // Data from the server
            const chartData = @json($analysis['chart_data'] ?? null);
            const salesData = @json($monthlySales);
            const categoryData = @json($categoryPerformance);
            const regionData = @json($revenueByRegion);
            const aiData = chartData;

            // Safely get data with fallbacks to prevent errors
            function safeGet(obj, path, fallback = null) {
                try {
                    const parts = path.split('.');
                    let result = obj;
                    for (const part of parts) {
                        if (result === null || result === undefined || typeof result !== 'object') {
                            return fallback;
                        }
                        result = result[part];
                    }
                    return result !== undefined ? result : fallback;
                } catch {
                    return fallback;
                }
            }

            // Initialize revenue chart with enhanced loading state
            function initRevenueChart() {
                const revenueChartLoader = document.getElementById('revenueChartLoader');
                if (revenueChartLoader) revenueChartLoader.style.display = 'block';

                let forecastMonths = [];
                if (aiData && aiData.forecast && aiData.forecast.length > 0) {
                    forecastMonths = aiData.forecast.map(item => ({
                        month: item.month + ' (Forecast)',
                        revenue: item.revenue,
                        isForecasted: true
                    }));
                } else if (salesData && salesData.length > 0) {
                    const lastDate = new Date(salesData[salesData.length - 1].date);
                    const lastThreeMonths = salesData.slice(-3);
                    const avgRevenue = lastThreeMonths.reduce((sum, item) => sum + item.revenue, 0) /
                        Math.max(1, lastThreeMonths.length);

                    for (let i = 1; i <= 6; i++) {
                        const forecastDate = new Date(lastDate);
                        forecastDate.setMonth(lastDate.getMonth() + i);
                        const month = forecastDate.toLocaleString('default', {
                            month: 'long'
                        });
                        const year = forecastDate.getFullYear();

                        forecastMonths.push({
                            month: `${month} ${year} (Forecast)`,
                            revenue: avgRevenue,
                            isForecasted: true
                        });
                    }
                }

                const combinedData = salesData ? [...salesData, ...forecastMonths] : [];

                if (combinedData.length === 0) {
                    if (revenueChartLoader) revenueChartLoader.textContent = 'No data available';
                    return;
                }

                const revenueCtx = document.getElementById('revenueChart');
                if (!revenueCtx) return;

                // Cache the chart configuration
                chartCache.configs.revenue = {
                    type: 'line',
                    data: {
                        labels: combinedData.map(item => item.month),
                        datasets: [{
                            label: 'Monthly Revenue',
                            data: combinedData.map(item => item.revenue),
                            backgroundColor: combinedData.map(item => item.isForecasted ?
                                'rgba(255, 237, 0, 0.05)' : 'rgba(0, 77, 255, 0.05)'),
                            borderColor: combinedData.map(item => item.isForecasted ?
                                COLORS.yellow.base : COLORS.blue.base),
                            pointBackgroundColor: combinedData.map(item => item.isForecasted ?
                                COLORS.yellow.base : COLORS.blue.base),
                            pointBorderColor: '#fff',
                            pointStyle: combinedData.map(item => item.isForecasted ? 'triangle' :
                                'circle'),
                            pointRadius: combinedData.map(item => item.isForecasted ? 5 : 3),
                            borderWidth: 2,
                            fill: true,
                            segment: {
                                borderDash: ctx => ctx.p0.parsed.x >= salesData.length - 1 ? [6, 6] :
                                    undefined,
                            }
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: false,
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.dataset.label || '';
                                        const isForecast = context.dataIndex >= salesData.length;
                                        return `${label}${isForecast ? ' (Forecast)' : ''}: ${formatCurrency(context.parsed.y)}`;
                                    }
                                }
                            },
                            legend: {
                                display: false
                            }
                        }
                    }
                };

                // Create the chart
                const revenueChart = new Chart(revenueCtx.getContext('2d'), chartCache.configs.revenue);
                if (revenueChartLoader) revenueChartLoader.style.display = 'none';
            }

            // Initialize category pie chart with optimized color handling
            function initCategoryChart() {
                const categoryPieChartLoader = document.getElementById('categoryPieChartLoader');
                if (categoryPieChartLoader) categoryPieChartLoader.style.display = 'block';

                if (!categoryData || categoryData.length === 0) {
                    if (categoryPieChartLoader) categoryPieChartLoader.textContent = 'No category data available';
                    return;
                }

                // Precompute and cache category colors
                const categoryColors = [];
                const categoryHoverColors = [];

                categoryData.forEach((category, index) => {
                    let colorIndex = index % colorPalette.length;
                    let colorConfig = colorPalette[colorIndex];

                    // Apply impact-based coloring if available
                    if (aiData && aiData.category_impact) {
                        const impact = aiData.category_impact.find(c =>
                            c.category.toLowerCase() === category.name.toLowerCase());

                        if (impact) {
                            if (impact.impact === 'positive') {
                                colorConfig = COLORS.positive;
                            } else if (impact.impact === 'negative') {
                                colorConfig = COLORS.negative;
                            } else if (impact.impact === 'neutral') {
                                colorConfig = COLORS.neutral;
                            }
                        }
                    }

                    categoryColors.push(colorConfig.base);
                    categoryHoverColors.push(colorConfig.hover);
                });

                // Cache the colors for potential reuse
                chartCache.colors.categories = {
                    base: categoryColors,
                    hover: categoryHoverColors
                };

                const categoryPieElement = document.getElementById('categoryPieChart');
                if (!categoryPieElement) return;

                chartCache.configs.category = {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.map(item => item.name),
                        datasets: [{
                            data: categoryData.map(item => item.revenue),
                            backgroundColor: categoryColors,
                            hoverBackgroundColor: categoryHoverColors,
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return `${label}: ${formatCurrency(value)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                };

                const categoryPieChart = new Chart(categoryPieElement.getContext('2d'), chartCache.configs
                    .category);
                if (categoryPieChartLoader) categoryPieChartLoader.style.display = 'none';
            }

            // Initialize region bar chart with optimized color logic
            function initRegionChart() {
                const regionBarChartLoader = document.getElementById('regionBarChartLoader');
                if (regionBarChartLoader) regionBarChartLoader.style.display = 'block';

                if (!regionData || regionData.length === 0) {
                    if (regionBarChartLoader) regionBarChartLoader.textContent = 'No region data available';
                    return;
                }

                let recommendedRegions = [];
                if (aiData && aiData.recommended_regions) {
                    recommendedRegions = aiData.recommended_regions.map(r => r.toLowerCase());
                }

                // Limit to top 5 regions for better visualization
                const topRegions = regionData.slice(0, 5);

                const regionBackgroundColors = topRegions.map((item, index) => {
                    return recommendedRegions.includes(item.region.toLowerCase()) ?
                        COLORS.positive.base : colorPalette[index % colorPalette.length].base;
                });

                const regionHoverColors = topRegions.map((item, index) => {
                    return recommendedRegions.includes(item.region.toLowerCase()) ?
                        COLORS.positive.hover : colorPalette[index % colorPalette.length].hover;
                });

                // Cache the colors
                chartCache.colors.regions = {
                    base: regionBackgroundColors,
                    hover: regionHoverColors
                };

                const regionBarElement = document.getElementById('regionBarChart');
                if (!regionBarElement) return;

                chartCache.configs.region = {
                    type: 'bar',
                    data: {
                        labels: topRegions.map(item => item.region),
                        datasets: [{
                            label: 'Regional Revenue',
                            data: topRegions.map(item => item.revenue),
                            backgroundColor: regionBackgroundColors,
                            hoverBackgroundColor: regionHoverColors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return formatCurrency(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = `Revenue: ${formatCurrency(context.parsed.y)}`;
                                        if (recommendedRegions.includes(context.label.toLowerCase())) {
                                            label += ' (Recommended)';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                };

                const regionBarChart = new Chart(regionBarElement.getContext('2d'), chartCache.configs.region);
                if (regionBarChartLoader) regionBarChartLoader.style.display = 'none';
            }

            // Initialize recommendations section with improved error handling
            function initRecommendations() {
                const recommendationsLoader = document.getElementById('recommendationsLoader');
                if (recommendationsLoader) recommendationsLoader.style.display = 'block';

                const recommendationsElement = document.getElementById('recommendations-content');
                if (!recommendationsElement) return;

                // Default recommendations as fallback
                let recommendations = [{
                        priority: 'high',
                        text: 'Focus on top-selling products and categories'
                    },
                    {
                        priority: 'medium',
                        text: 'Optimize inventory based on regional demand'
                    },
                    {
                        priority: 'low',
                        text: 'Monitor competitor pricing and promotions'
                    }
                ];

                if (aiData) {
                    // Determine which data source to use for recommendations
                    if (aiData.top_products && aiData.top_products.length > 0) {
                        recommendations = aiData.top_products.map(product => ({
                            priority: product.potential || 'medium',
                            text: `Focus on <strong>${product.product_name}</strong> in the ${product.category} category`
                        }));
                    } else if (aiData.competitor_comparison && aiData.competitor_comparison.length > 0) {
                        recommendations = aiData.competitor_comparison.map(competitor => {
                            // Fix: Properly parse market_share as a number
                            // Ensure it's a number by converting to string first then parsing
                            // Handle both string and number formats
                            const marketShareValue = competitor.market_share;
                            let marketShare = 0;

                            if (typeof marketShareValue === 'string') {
                                // Remove any % sign and parse
                                marketShare = parseFloat(marketShareValue.replace('%', ''));
                            } else if (typeof marketShareValue === 'number') {
                                marketShare = marketShareValue;
                            }

                            // Ensure it's not NaN after parsing
                            if (isNaN(marketShare)) marketShare = 0;

                            return {
                                priority: marketShare > 25 ? 'high' : (marketShare > 10 ? 'medium' : 'low'),
                                text: `Address competition from <strong>${competitor.competitor}</strong> with ${marketShare}% market share`
                            };
                        });
                    } else if (aiData.regional_performance && aiData.regional_performance.length > 0) {
                        recommendations = aiData.regional_performance.slice(0, 3).map(region => {
                            const revenue = parseFloat(region.revenue) || 0;
                            return {
                                priority: 'medium',
                                text: `Expand presence in <strong>${region.region}</strong> region with forecasted revenue of ${formatCurrency(revenue)}`
                            };
                        });
                    } else if (aiData.recommendations && aiData.recommendations.length > 0) {
                        // Direct recommendations if available
                        recommendations = aiData.recommendations;
                    }
                }

                // Clear and repopulate recommendations
                recommendationsElement.innerHTML = '';
                const recList = document.createElement('ul');
                recList.className = 'recommendation-items';

                recommendations.forEach(rec => {
                    const item = document.createElement('li');
                    item.className = `${rec.priority}-priority`;
                    item.innerHTML = rec.text;
                    recList.appendChild(item);
                });

                recommendationsElement.appendChild(recList);
                if (recommendationsLoader) recommendationsLoader.style.display = 'none';
            }

            // Export functionality
            function setupExport() {
                const exportBtn = document.getElementById('exportBtn');
                if (!exportBtn) return;

                exportBtn.addEventListener('click', function() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');

                    // Set loading state
                    exportBtn.disabled = true;
                    exportBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin fa-sm text-white-50"></i> Exporting...';

                    const content = document.querySelector('.content');

                    html2canvas(content, {
                        scale: 2,
                        logging: false,
                        useCORS: true,
                        allowTaint: true
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const imgWidth = 210; // A4 width in mm
                        const pageHeight = 297; // A4 height in mm
                        const imgHeight = canvas.height * imgWidth / canvas.width;
                        let heightLeft = imgHeight;
                        let position = 0;

                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;

                        while (heightLeft >= 0) {
                            position = heightLeft - imgHeight;
                            pdf.addPage();
                            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                            heightLeft -= pageHeight;
                        }

                        pdf.save('market-trend-analysis.pdf');

                        // Reset button state
                        exportBtn.disabled = false;
                        exportBtn.innerHTML =
                            '<i class="fas fa-download fa-sm text-white-50"></i> Export Report';
                    });
                });
            }

            // Initialize all components with error handling
            try {
                initRevenueChart();
                initCategoryChart();
                initRegionChart();
                initRecommendations();
                setupExport();
            } catch (error) {
                console.error('Error initializing charts:', error);
                document.querySelectorAll('.chart-loader').forEach(loader => {
                    loader.textContent = 'Error loading chart data';
                    loader.style.display = 'block';
                });
            }
        });
    </script>

    <style>
        .chart-loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #666;
            font-style: italic;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            border-radius: 4px;
            z-index: 10;
        }

        .chart-area,
        .chart-bar,
        .chart-pie {
            position: relative;
            height: 300px;
        }

        .prediction-content,
        .explanation-content,
        .recommendations-content {
            padding: 15px;
        }

        .recommendation-items {
            list-style: none;
            padding: 0;
        }

        .recommendation-items li {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            background-color: #f8f9fc;
        }

        .high-priority {
            border-left: 4px solid #E40303;
            /* Red from pride flag */
            padding-left: 10px;
        }

        .medium-priority {
            border-left: 4px solid #FFED00;
            /* Yellow from pride flag */
            padding-left: 10px;
        }

        .low-priority {
            border-left: 4px solid #008026;
            /* Green from pride flag */
            padding-left: 10px;
        }

        .highlight-term {
            font-weight: 600;
            color: #004DFF;
            /* Blue from pride flag */
        }

        .badge-success {
            background-color: #008026;
            /* Green from pride flag */
        }

        .badge-warning {
            background-color: #FFED00;
            /* Yellow from pride flag */
        }

        .badge-info {
            background-color: #004DFF;
            /* Blue from pride flag */
        }

        .section-heading {
            margin-top: 20px;
            color: #750787;
            /* Indigo from pride flag */
        }

        .subsection-heading {
            margin-top: 15px;
            color: #9C27B0;
            /* Violet from pride flag */
        }

        /* Responsive improvements */
        @media (max-width: 768px) {

            .chart-area,
            .chart-bar,
            .chart-pie {
                height: 250px;
            }
        }
    </style>
@endsection
