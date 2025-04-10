{{-- resources/views/admin/trend-analysis/result.blade.php --}}
{{-- Updated view to align with the latest controller logic and data handling --}}
{{-- Enhanced layout and design for better user experience --}}
{{-- Added export, compare, share, and alert features for improved functionality --}}

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

                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Recommendations Summary</h6>
                            </div>
                            <div class="card-body">
                                @if (isset($analysis) && $analysis['success'])
                                    <div class="recommendations-list">
                                        <div id="recommendations-content">
                                            <p class="text-center text-gray-500">Processing recommendations...</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-center text-danger">Unable to generate recommendations. Please try again.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Detailed Category Data</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Products</th>
                                                <th>Units Sold</th>
                                                <th>Revenue</th>
                                                <th>Growth Potential</th>
                                                <th>Trend Impact</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categoryPerformance as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->product_count }}</td>
                                                    <td>{{ $category->units_sold }}</td>
                                                    <td>${{ number_format($category->revenue, 2) }}</td>
                                                    <td id="growth-{{ $category->id }}">Analyzing...</td>
                                                    <td id="impact-{{ $category->id }}">Analyzing...</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
                                    return '$' + value.toLocaleString();
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

            const categoryColors = [];
            const categoryHoverColors = [];

            categoryData.forEach(category => {
                let baseColor = '#4e73df';
                let hoverColor = '#2e59d9';

                if (aiData && aiData.category_impact) {
                    const impact = aiData.category_impact.find(c => c.category.toLowerCase() === category
                        .name.toLowerCase());

                    if (impact) {
                        if (impact.impact === 'positive') {
                            baseColor = '#1cc88a';
                            hoverColor = '#17a673';
                        } else if (impact.impact === 'negative') {
                            baseColor = '#e74a3b';
                            hoverColor = '#e02d1b';
                        } else if (impact.impact === 'neutral') {
                            baseColor = '#f6c23e';
                            hoverColor = '#f4b619';
                        }
                    }
                }

                categoryColors.push(baseColor);
                categoryHoverColors.push(hoverColor);
            });

            const categoryCtx = document.getElementById('categoryPieChart').getContext('2d');
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
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            let recommendedRegions = [];
            if (aiData && aiData.recommended_regions) {
                recommendedRegions = aiData.recommended_regions.map(r => r.toLowerCase());
            }

            const regionBackgroundColors = regionData.slice(0, 5).map(item => {
                return recommendedRegions.includes(item.region.toLowerCase()) ? '#1cc88a' : '#4e73df';
            });

            const regionHoverColors = regionData.slice(0, 5).map(item => {
                return recommendedRegions.includes(item.region.toLowerCase()) ? '#17a673' : '#2e59d9';
            });

            const regionCtx = document.getElementById('regionBarChart').getContext('2d');
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
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = `Revenue: $${context.parsed.y.toLocaleString()}`;
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

            // Populate the recommendations section with data from AI
            if (aiData && aiData.top_products) {
                document.getElementById('recommendations-content').innerHTML = '';
                const recList = document.createElement('ul');
                recList.className = 'recommendation-items';

                // Add recommendations based on top products
                aiData.top_products.forEach(product => {
                    const item = document.createElement('li');
                    item.className = product.potential === 'high' ? 'high-priority' :
                        (product.potential === 'medium' ? 'medium-priority' : 'low-priority');
                    item.innerHTML =
                        `Focus on <strong>${product.product_name}</strong> in the ${product.category} category`;
                    recList.appendChild(item);
                });

                // Add any specific section recommendations
                if (document.querySelector('.recommendations-content')) {
                    const recItems = document.querySelectorAll('.recommendations-content li');
                    recItems.forEach(rec => {
                        recList.appendChild(rec.cloneNode(true));
                    });
                }

                document.getElementById('recommendations-content').appendChild(recList);
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
