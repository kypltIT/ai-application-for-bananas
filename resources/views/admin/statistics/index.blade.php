{{--
Created: Statistics page view
Features:
- Date range filter
- Category filter
- Statistical charts and data display
--}}

@extends('layouts.admin.app')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid px-4">
                <h1 class="mt-4">Profit Statistics</h1>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>From Date:</label>
                                    <input type="date" class="form-control" id="start_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>To Date:</label>
                                    <input type="date" class="form-control" id="end_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category:</label>
                                    <select class="form-control" id="category">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-primary" onclick="fetchStatistics()">Filter Data</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">
                                Total Revenue
                                <h4 id="total-revenue">0 đ</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">
                                <h5 class="mb-3">Total Orders by Category</h5>
                                <div id="orders-by-category-list">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                                <hr class="border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Total:</span>
                                    <h4 id="total-orders" class="mb-0">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">
                                Average Revenue per Order
                                <h4 id="average-revenue">0 đ</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-1"></i>
                                Revenue by Category
                            </div>
                            <div class="card-body">
                                <canvas id="revenueByCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-1"></i>
                                Orders by Category
                            </div>
                            <div class="card-body">
                                <canvas id="ordersByCategoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-line me-1"></i>
                                Revenue by Month
                            </div>
                            <div class="card-body">
                                <canvas id="revenueByMonthChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                let categoryRevenueChart = null;
                let categoryOrdersChart = null;
                let monthChart = null;

                function fetchStatistics() {
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    const category = document.getElementById('category').value;

                    fetch(`/admin/statistics/data?start_date=${startDate}&end_date=${endDate}&category=${category}`)
                        .then(response => response.json())
                        .then(data => {
                            updateStatistics(data);
                            updateCharts(data);
                        });
                }

                function updateStatistics(data) {
                    document.getElementById('total-revenue').textContent = formatCurrency(data.total_revenue);
                    document.getElementById('average-revenue').textContent = formatCurrency(data.average_revenue);

                    const ordersList = document.getElementById('orders-by-category-list');
                    ordersList.innerHTML = '';

                    Object.entries(data.orders_by_category).forEach(([category, count]) => {
                        const item = document.createElement('div');
                        item.className = 'd-flex justify-content-between align-items-center mb-2';
                        item.innerHTML = `
                            <span>${category}:</span>
                            <span class="fw-bold">${count}</span>
                        `;
                        ordersList.appendChild(item);
                    });

                    document.getElementById('total-orders').textContent = data.total_orders;
                }

                function updateCharts(data) {
                    // Update revenue by category chart
                    if (categoryRevenueChart) categoryRevenueChart.destroy();
                    const categoryRevenueCtx = document.getElementById('revenueByCategoryChart');
                    categoryRevenueChart = new Chart(categoryRevenueCtx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(data.revenue_by_category),
                            datasets: [{
                                label: 'Revenue by Category',
                                data: Object.values(data.revenue_by_category),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)'
                            }]
                        },
                        options: {
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
                                            return 'Revenue: ' + formatCurrency(context.raw);
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Update orders by category chart
                    if (categoryOrdersChart) categoryOrdersChart.destroy();
                    const categoryOrdersCtx = document.getElementById('ordersByCategoryChart');
                    categoryOrdersChart = new Chart(categoryOrdersCtx, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(data.orders_by_category),
                            datasets: [{
                                label: 'Orders by Category',
                                data: Object.values(data.orders_by_category),
                                backgroundColor: 'rgba(75, 192, 192, 0.5)'
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Orders: ' + context.raw;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Update month chart
                    if (monthChart) monthChart.destroy();
                    const monthCtx = document.getElementById('revenueByMonthChart');
                    monthChart = new Chart(monthCtx, {
                        type: 'line',
                        data: {
                            labels: Object.keys(data.revenue_by_month),
                            datasets: [{
                                label: 'Revenue by Month',
                                data: Object.values(data.revenue_by_month),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                tension: 0.1
                            }]
                        },
                        options: {
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
                                            return 'Revenue: ' + formatCurrency(context.raw);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                function formatCurrency(amount) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(amount);
                }

                // Initial load
                fetchStatistics();
            </script>
        </div>
    </div>
@endsection
