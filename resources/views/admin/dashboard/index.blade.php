@extends('layouts.admin.app')
@section('title', 'Dashboard')
@section('content')

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Dashboard Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card dash-widget shadow-sm">
                        <div class="card-body text-center">
                            <div class="dash-widget-header">
                                <div class="dash-content">
                                    <i class="fas fa-box fa-2x mb-2"></i>
                                    <h4 class="font-weight-bold text-uppercase">Total Products</h4>
                                    <h2 style="color: #2E37A4;">{{ $totalProducts }}</h2>
                                    <p class="text-muted">Number of Products Available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card dash-widget shadow-sm">
                        <div class="card-body text-center">
                            <div class="dash-widget-header">
                                <div class="dash-content">
                                    <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                    <h4 class="font-weight-bold text-uppercase">Total Orders</h4>
                                    <h2 style="color: #2E37A4;">{{ $totalOrders }}</h2>
                                    <p class="text-muted">Number of Orders Processed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card dash-widget shadow-sm">
                        <div class="card-body text-center">
                            <div class="dash-widget-header">
                                <div class="dash-content">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h4 class="font-weight-bold text-uppercase">Total Customers</h4>
                                    <h2 style="color: #2E37A4;">{{ $totalCustomers }}</h2>
                                    <p class="text-muted">Number of Registered Customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card dash-widget shadow-sm">
                        <div class="card-body text-center">
                            <div class="dash-widget-header">
                                <div class="dash-content">
                                    <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                                    <h4 class="font-weight-bold text-uppercase">Total Revenue</h4>
                                    <h2 style="color: #2E37A4;">{{ number_format($totalRevenue) }}VND</h2>
                                    <p class="text-muted">Total Revenue Generated</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Monthly Sales Chart -->
                <div class="col-md-7">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Monthly Sales</h4>
                        </div>
                        <div class="card-body">
                            <div id="sales-chart"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Quick Stats</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="stats-info">
                                        <h6>Blog Posts</h6>
                                        <h4>{{ $totalBlogs }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="stats-info">
                                        <h6>Unread Messages</h6>
                                        <h4>{{ $unreadContacts }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Blog Posts -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Recent Blog Posts</h4>
                        </div>
                        <div class="card-body">
                            <ul class="activity-feed">
                                @foreach ($recentBlogs as $blog)
                                    <li class="feed-item">
                                        <div class="feed-date">{{ $blog->created_at->format('M d') }}</div>
                                        <span class="feed-text">
                                            <strong>{{ Str::limit($blog->title, 30) }}</strong>
                                            <span class="badge bg-info">{{ $blog->blogCategory->name }}</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location-based Sales Charts -->
            <div class="row">
                <!-- City Sales Chart -->
                <div class="col-md-4">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Top Cities by Sales</h4>
                        </div>
                        <div class="card-body">
                            <div id="city-sales-chart"></div>
                        </div>
                    </div>
                </div>

                <!-- District Sales Chart -->
                <div class="col-md-4">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Top Districts by Sales</h4>
                        </div>
                        <div class="card-body">
                            <div id="district-sales-chart"></div>
                        </div>
                    </div>
                </div>

                <!-- Ward Sales Chart -->
                <div class="col-md-4">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Top Wards by Sales</h4>
                        </div>
                        <div class="card-body">
                            <div id="ward-sales-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Orders -->
                <div class="col-md-8">
                    <div class="card card-table">
                        <div class="card-header">
                            <h4 class="card-title">Recent Orders</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border-0  datatable1 mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td><a
                                                        href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_code }}</a>
                                                </td>
                                                <td>{{ $order->customer->name }}</td>
                                                <td>${{ number_format($order->total_price, 2, '.', ',') }}</td>
                                                <td>
                                                    @if ($order->status == 'completed')
                                                        <span class="badge bg-success">Completed</span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge bg-warning">Processing</span>
                                                    @elseif($order->status == 'ordered')
                                                        <span class="badge bg-info">Ordered</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No orders found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Customers -->
                <div class="col-md-4">
                    <div class="card card-table">
                        <div class="card-header">
                            <h4 class="card-title">Recent Customers</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border-0 datatable1 mb-0 ">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentCustomers as $customer)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.customers.show', $customer->id) }}">
                                                        {{ $customer->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $customer->email }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center">No customers found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Monthly sales chart
        var monthlySalesData = @json($monthlySales);

        var months = monthlySalesData.map(item => item.month);
        var revenues = monthlySalesData.map(item => parseInt(item.revenue));

        var salesChartOptions = {
            series: [{
                name: 'Revenue',
                data: revenues
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            colors: ['#2E37A4'],
            xaxis: {
                categories: months
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return '$' + val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            fill: {
                opacity: 0.2
            }
        };

        var salesChart = new ApexCharts(document.querySelector("#sales-chart"), salesChartOptions);
        salesChart.render();

        // City sales chart
        var citySalesData = @json($citySales);
        var cityNames = citySalesData.map(item => item.name);
        var cityRevenues = citySalesData.map(item => parseInt(item.revenue));

        var citySalesChartOptions = {
            series: [{
                name: 'Revenue',
                data: cityRevenues
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true
            },
            colors: ['#3b7ddd'],
            xaxis: {
                categories: cityNames,
                labels: {
                    formatter: function(val) {
                        return '$' + val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' đ';
                    }
                }
            }
        };

        var citySalesChart = new ApexCharts(document.querySelector("#city-sales-chart"), citySalesChartOptions);
        citySalesChart.render();

        // District sales chart
        var districtSalesData = @json($districtSales);
        var districtNames = districtSalesData.map(item => item.name);
        var districtRevenues = districtSalesData.map(item => parseInt(item.revenue));

        var districtSalesChartOptions = {
            series: [{
                name: 'Revenue',
                data: districtRevenues
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true
            },
            colors: ['#28a745'],
            xaxis: {
                categories: districtNames,
                labels: {
                    formatter: function(val) {
                        return '$' + val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return '$' + val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        };

        var districtSalesChart = new ApexCharts(document.querySelector("#district-sales-chart"),
            districtSalesChartOptions);
        districtSalesChart.render();

        // Ward sales chart
        var wardSalesData = @json($wardSales);
        var wardNames = wardSalesData.map(item => item.name);
        var wardRevenues = wardSalesData.map(item => parseInt(item.revenue));

        var wardSalesChartOptions = {
            series: [{
                name: 'Revenue',
                data: wardRevenues
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: true
            },
            colors: ['#fd7e14'],
            xaxis: {
                categories: wardNames,
                labels: {
                    formatter: function(val) {
                        return '$' + val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return '$' + val.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        };

        var wardSalesChart = new ApexCharts(document.querySelector("#ward-sales-chart"), wardSalesChartOptions);
        wardSalesChart.render();
    });
</script>
