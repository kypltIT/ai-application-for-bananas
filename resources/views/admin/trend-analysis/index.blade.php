{{-- resources/views/admin/trend-analysis/index.blade.php --}}
{{-- Updated view to add forecast period input field and improve user interface --}}
{{-- Added ability to customize forecast period rather than fixed 6-month period --}}

@extends('layouts.admin.app')

@section('title', 'Fashion Trend Analysis')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Fashion Trend Analysis</h1>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Analyze Current Fashion Trends</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.trend-analysis.analyze') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="forecast_period">Forecast Period (months)</label>
                                        <select class="form-control" id="forecast_period" name="forecast_period">
                                            <option value="3">3 months</option>
                                            <option value="6" selected>6 months</option>
                                            <option value="9">9 months</option>
                                            <option value="12">12 months</option>
                                        </select>
                                        <small class="form-text text-muted">Select how many months you want to forecast into
                                            the future</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Analyze Trend Impact</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Historical Sales Data (Last 12 Months)</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="monthlySalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Product Category Performance</h6>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categoryPerformance as $category)
                                                <tr>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->product_count }}</td>
                                                    <td>{{ $category->units_sold }}</td>
                                                    <td>${{ number_format($category->revenue, 2) }}</td>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart for monthly sales
            const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');

            const salesData = @json($monthlySales);

            // Debug: Check if data is available
            console.log('Monthly Sales Data:', salesData);

            if (salesData && salesData.length > 0) {
                const monthlySalesChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: salesData.map(item => item.month),
                        datasets: [{
                            label: 'Monthly Revenue',
                            data: salesData.map(item => item.revenue),
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 2,
                            fill: true
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
                                        return 'Revenue: $' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                // If no data, display a message
                document.querySelector('.chart-area').innerHTML =
                    '<div class="text-center p-4">No sales data available for the selected period.</div>';
            }
        });
    </script>
@endsection
