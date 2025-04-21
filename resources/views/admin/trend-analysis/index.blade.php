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
                                <form id="analyzeForm" action="{{ route('admin.trend-analysis.analyze') }}" method="POST">
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
                                    <button type="submit" class="btn btn-primary" id="analyzeButton">
                                        <span class="normal-state">Analyze Trend Impact</span>
                                        <span class="loading-state" style="display: none;">
                                            <i class="fas fa-spinner fa-spin"></i> Analyzing...
                                        </span>
                                    </button>
                                </form>

                                <!-- Skeleton Loading -->
                                <div id="skeletonLoading" style="display: none;" class="mt-4">
                                    <div class="skeleton-item mb-4"></div>
                                    <div class="skeleton-item mb-4" style="width: 80%;"></div>
                                    <div class="skeleton-item mb-4" style="width: 90%;"></div>
                                    <div class="skeleton-item mb-4" style="width: 70%;"></div>
                                </div>
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
                                <!-- Section heading for regional performance -->
                                <h6 class="m-0 font-weight-bold text-primary">Regional Performance ({{ now()->subMonths(12)->format('F Y') }} - {{ now()->format('F Y') }})</h6>
                            </div>
                            <div class="card-body">
                                @if (!empty($revenueByRegion) && count($revenueByRegion) > 0)
                                    <div class="chart-bar">
                                        <canvas id="regionBarChart"></canvas>
                                    </div>
                                @else
                                    <p class="text-center text-gray-500">No regional data available for the selected period.</p>
                                @endif
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
                                        return  value.toLocaleString() + ' VND';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Revenue: ' + context.parsed.y.toLocaleString() + 'VND';
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

            // Chart for regional performance
            const regionData = @json($revenueByRegion);

            if (regionData && regionData.length > 0) {
                const regionCtx = document.getElementById('regionBarChart').getContext('2d');
                const regionBarChart = new Chart(regionCtx, {
                    type: 'bar',
                    data: {
                        labels: regionData.map(item => item.region),
                        datasets: [{
                            label: 'Regional Revenue',
                            data: regionData.map(item => item.revenue),
                            backgroundColor: 'rgba(78, 115, 223, 0.8)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 1
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
                                        return 'Revenue: ' + context.parsed.y.toLocaleString() + ' VND';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection

@push('styles')
<style>
    .skeleton-item {
        height: 20px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    #analyzeButton {
        min-width: 150px;
    }

    .loading-state {
        white-space: nowrap;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('analyzeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const button = document.getElementById('analyzeButton');
        const normalState = button.querySelector('.normal-state');
        const loadingState = button.querySelector('.loading-state');
        const skeletonLoading = document.getElementById('skeletonLoading');
        
        // Disable button and show loading state
        button.disabled = true;
        normalState.style.display = 'none';
        loadingState.style.display = 'inline';
        skeletonLoading.style.display = 'block';

        // Submit form using AJAX
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.trend-analysis.result") }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.disabled = false;
            normalState.style.display = 'inline';
            loadingState.style.display = 'none';
            skeletonLoading.style.display = 'none';
            alert('An error occurred. Please try again.');
        });
    });
</script>
@endpush
