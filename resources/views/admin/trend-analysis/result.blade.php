{{-- resources/views/admin/trend-analysis/result.blade.php --}}
{{-- Updated view to align with the latest controller logic and data handling --}}
{{-- Enhanced layout and design for better user experience --}}
{{-- Added export, compare, share, and alert features for improved functionality --}}
{{-- Fixed regionBarChart initialization by removing references to non-existent elements --}}
{{-- Modified recommendations section to display content from analysis rather than relying on top_products data --}}
{{-- Improved color coding for Regional Performance and Category Performance charts --}}
{{-- Fixed NaN% issue in category chart tooltips by adding null checks for calculations --}}

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
                                <div class="chart-area">
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
                                <div class="chart-bar">
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
                                <div class="chart-pie">
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
                                    <!-- Recommendations will be populated here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compare Analysis Modal -->


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($analysis['chart_data'] ?? null);
            const salesData = @json($monthlySales);
            const categoryData = @json($categoryPerformance);
            const regionData = @json($revenueByRegion);

            const aiData = chartData;

            if (aiData && aiData.category_impact) {
                categoryData.forEach(category => {
                    const categoryName = category.name.toLowerCase();
                    const categoryImpact = aiData.category_impact.find(c => c.category.toLowerCase() ===
                        categoryName);

                    if (categoryImpact) {
                        const growthElement = document.getElementById(`growth-${category.id}`);
                        const impactElement = document.getElementById(`impact-${category.id}`);

                        if (growthElement) {
                            const growthText = categoryImpact.growth_percentage > 0 ?
                                `+${categoryImpact.growth_percentage}%` :
                                `${categoryImpact.growth_percentage}%`;
                            growthElement.textContent = growthText;
                            growthElement.classList.add(categoryImpact.growth_percentage > 0 ?
                                'text-success' : (categoryImpact.growth_percentage < 0 ? 'text-danger' :
                                    'text-warning'));
                        }

                        if (impactElement) {
                            impactElement.textContent = categoryImpact.impact.charAt(0).toUpperCase() +
                                categoryImpact.impact.slice(1);
                            impactElement.classList.add(categoryImpact.impact === 'positive' ?
                                'text-success' : (categoryImpact.impact === 'negative' ? 'text-danger' :
                                    'text-warning'));
                        }
                    }
                });
            }

            let forecastMonths = [];
            if (aiData && aiData.forecast && aiData.forecast.length > 0) {
                forecastMonths = aiData.forecast.map(item => ({
                    month: item.month + ' (Forecast)',
                    revenue: item.revenue,
                    isForecasted: true
                }));
            } else {
                const lastDate = new Date(salesData[salesData.length - 1].date);
                for (let i = 1; i <= 6; i++) {
                    const forecastDate = new Date(lastDate);
                    forecastDate.setMonth(lastDate.getMonth() + i);
                    const month = forecastDate.toLocaleString('default', {
                        month: 'long'
                    });
                    const year = forecastDate.getFullYear();
                    const lastThreeMonths = salesData.slice(-3);
                    const avgRevenue = lastThreeMonths.reduce((sum, item) => sum + item.revenue, 0) /
                        lastThreeMonths.length;

                    forecastMonths.push({
                        month: `${month} ${year} (Forecast)`,
                        revenue: avgRevenue,
                        isForecasted: true
                    });
                }
            }

            const combinedData = [...salesData, ...forecastMonths];

            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: combinedData.map(item => item.month),
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: combinedData.map(item => item.revenue),
                        backgroundColor: combinedData.map(item => item.isForecasted ?
                            'rgba(255, 205, 86, 0.05)' : 'rgba(78, 115, 223, 0.05)'),
                        borderColor: combinedData.map(item => item.isForecasted ?
                            'rgba(255, 205, 86, 1)' : 'rgba(78, 115, 223, 1)'),
                        pointBackgroundColor: combinedData.map(item => item.isForecasted ?
                            'rgba(255, 205, 86, 1)' : 'rgba(78, 115, 223, 1)'),
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
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' VND';
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
                                    return `${label}${isForecast ? ' (Forecast)' : ''}: $${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Generate distinct colors for categories
            const categoryColors = [];
            const categoryHoverColors = [];
            const colorPalette = [{
                    base: '#4e73df',
                    hover: '#2e59d9'
                }, // Blue
                {
                    base: '#1cc88a',
                    hover: '#17a673'
                }, // Green
                {
                    base: '#e74a3b',
                    hover: '#e02d1b'
                }, // Red
                {
                    base: '#f6c23e',
                    hover: '#f4b619'
                }, // Yellow
                {
                    base: '#36b9cc',
                    hover: '#258391'
                }, // Cyan
                {
                    base: '#6f42c1',
                    hover: '#553285'
                }, // Purple
                {
                    base: '#fd7e14',
                    hover: '#d46a0a'
                }, // Orange
                {
                    base: '#20c9a6',
                    hover: '#169b7f'
                } // Teal
            ];

            categoryData.forEach((category, index) => {
                // Use the color palette in sequence first, then based on impact if available
                let colorIndex = index % colorPalette.length;
                let baseColor = colorPalette[colorIndex].base;
                let hoverColor = colorPalette[colorIndex].hover;

                if (aiData && aiData.category_impact) {
                    const impact = aiData.category_impact.find(c => c.category.toLowerCase() === category
                        .name.toLowerCase());

                    if (impact) {
                        if (impact.impact === 'positive') {
                            baseColor = '#1cc88a'; // Green for positive
                            hoverColor = '#17a673';
                        } else if (impact.impact === 'negative') {
                            baseColor = '#e74a3b'; // Red for negative
                            hoverColor = '#e02d1b';
                        } else if (impact.impact === 'neutral') {
                            baseColor = '#f6c23e'; // Yellow for neutral
                            hoverColor = '#f4b619';
                        }
                    }
                }

                categoryColors.push(baseColor);
                categoryHoverColors.push(hoverColor);
            });

            // Only initialize the categoryPieChart if the element exists
            const categoryPieElement = document.getElementById('categoryPieChart');
            if (categoryPieElement) {
                const categoryCtx = categoryPieElement.getContext('2d');
                const categoryPieChart = new Chart(categoryCtx, {
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
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        // Avoid division by zero
                                        let percentage = 0;
                                        if (total > 0) {
                                            percentage = Math.round((value / total) * 100);
                                        }
                                        return `${label}: ${value.toLocaleString()} VND (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Generate colors for regions
            const regionColors = [
                '#4e73df', // Blue
                '#1cc88a', // Green
                '#e74a3b', // Red
                '#f6c23e', // Yellow
                '#36b9cc' // Cyan
            ];

            let recommendedRegions = [];
            if (aiData && aiData.recommended_regions) {
                recommendedRegions = aiData.recommended_regions.map(r => r.toLowerCase());
            }

            const regionBackgroundColors = regionData.slice(0, 5).map((item, index) => {
                // If it's a recommended region, use green, otherwise use colors from the palette
                return recommendedRegions.includes(item.region.toLowerCase()) ?
                    '#1cc88a' : regionColors[index % regionColors.length];
            });

            const regionHoverColors = regionData.slice(0, 5).map((item, index) => {
                // Darker version of the background color for hover
                return recommendedRegions.includes(item.region.toLowerCase()) ?
                    '#17a673' : (regionColors[index % regionColors.length] === '#4e73df' ? '#2e59d9' :
                        regionColors[index % regionColors.length] === '#1cc88a' ? '#17a673' :
                        regionColors[index % regionColors.length] === '#e74a3b' ? '#e02d1b' :
                        regionColors[index % regionColors.length] === '#f6c23e' ? '#f4b619' :
                        '#258391');
            });

            // Only initialize the regionBarChart if the element exists
            const regionBarElement = document.getElementById('regionBarChart');
            if (regionBarElement) {
                const regionCtx = regionBarElement.getContext('2d');
                const regionBarChart = new Chart(regionCtx, {
                    type: 'bar',
                    data: {
                        labels: regionData.slice(0, 5).map(item => item.region),
                        datasets: [{
                            label: 'Regional Revenue',
                            data: regionData.slice(0, 5).map(item => item.revenue),
                            backgroundColor: regionBackgroundColors,
                            hoverBackgroundColor: regionHoverColors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString() + ' VND';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = `Revenue: ${context.parsed.y.toLocaleString()} VND`;
                                        if (recommendedRegions.includes(context.label.toLowerCase())) {
                                            label += ' (Recommended)';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Populate the recommendations section with data from AI if the element exists
            const recommendationsElement = document.getElementById('recommendations-content');
            if (recommendationsElement) {
                // Default recommendations in case the AI data doesn't have specific recommendations
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

                // Check if aiData has top_products or competitor_comparison for better recommendations
                if (aiData) {
                    if (aiData.top_products) {
                        // Use the existing top_products logic
                        recommendations = aiData.top_products.map(product => ({
                            priority: product.potential || 'medium',
                            text: `Focus on <strong>${product.product_name}</strong> in the ${product.category} category`
                        }));
                    } else if (aiData.competitor_comparison && aiData.competitor_comparison.length > 0) {
                        // Create recommendations based on competitor comparison
                        recommendations = aiData.competitor_comparison.map(competitor => {
                            // Ensure market_share is a number to avoid NaN
                            const marketShare = parseFloat(competitor.market_share) || 0;
                            return {
                                priority: marketShare > 25 ? 'high' : (marketShare > 10 ? 'medium' : 'low'),
                                text: `Address competition from <strong>${competitor.competitor}</strong> with ${marketShare}% market share`
                            };
                        });
                    } else if (aiData.regional_performance && aiData.regional_performance.length > 0) {
                        // Create recommendations based on regional performance
                        recommendations = aiData.regional_performance.slice(0, 3).map(region => {
                            // Ensure revenue is a number
                            const revenue = parseFloat(region.revenue) || 0;
                            return {
                                priority: 'medium',
                                text: `Expand presence in <strong>${region.region}</strong> region with forecasted revenue of ${revenue.toLocaleString()} VND`
                            };
                        });
                    }
                }

                recommendationsElement.innerHTML = '';
                const recList = document.createElement('ul');
                recList.className = 'recommendation-items';

                // Add recommendations based on available data
                recommendations.forEach(rec => {
                    const item = document.createElement('li');
                    item.className = `${rec.priority}-priority`;
                    item.innerHTML = rec.text;
                    recList.appendChild(item);
                });

                recommendationsElement.appendChild(recList);
            }
        });
    </script>

    <style>
        .prediction-content,
        .explanation-content,
        .recommendations-content {
            padding: 15px;
        }

        .tab-content ul {
            margin-left: 20px;
        }

        .tab-content li {
            margin-bottom: 10px;
        }

        .high-priority {
            border-left: 4px solid #e74a3b;
            padding-left: 10px;
        }

        .medium-priority {
            border-left: 4px solid #f6c23e;
            padding-left: 10px;
        }

        .low-priority {
            border-left: 4px solid #1cc88a;
            padding-left: 10px;
        }

        .highlight-term {
            font-weight: 600;
            color: #4e73df;
        }

        .badge-success {
            background-color: #1cc88a;
        }

        .badge-warning {
            background-color: #f6c23e;
        }

        .badge-info {
            background-color: #36b9cc;
        }

        .section-heading {
            margin-top: 20px;
            color: #2e59d9;
        }

        .subsection-heading {
            margin-top: 15px;
            color: #5a5c69;
        }

        /* Ensure tabs display correctly */
        .nav-tabs .nav-link {
            font-weight: bold;
        }

        .nav-tabs .nav-link.active {
            background-color: #f8f9fc;
            border-color: #dddfeb #dddfeb #f8f9fc;
        }
    </style>
@endsection
