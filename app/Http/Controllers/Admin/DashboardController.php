<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Address;
use App\Models\City;
use App\Models\District;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalBlogs = Blog::count();
        // Changed unreadContacts to count the most recent contacts instead of is_read
        $unreadContacts = Contact::orderBy('created_at', 'desc')->count();

        // Recent orders
        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent customers
        $recentCustomers = Customer::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Monthly sales for chart
        $monthlySales = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('SUM(total_price) as revenue'),
                DB::raw("DATE_FORMAT(created_at, '%M') as month")
            )
            ->groupBy('month')
            ->orderBy(DB::raw("MIN(created_at)"), 'ASC') // Changed to order by MIN(created_at) to avoid SQL error
            ->get();

        // Recent blogs
        $recentBlogs = Blog::with('blogCategory')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // City sales statistics
        $citySales = Order::join('addresses', 'orders.id', '=', 'addresses.order_id')
            ->join('cities', 'addresses.city', '=', 'cities.id')
            ->where('orders.status', 'completed')
            ->select(
                'cities.full_name_en as name',
                DB::raw('SUM(orders.total_price) as revenue'),
                DB::raw('COUNT(orders.id) as count')
            )
            ->groupBy('cities.id', 'cities.full_name_en')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        // District sales statistics
        $districtSales = Order::join('addresses', 'orders.id', '=', 'addresses.order_id')
            ->join('districts', 'addresses.district', '=', 'districts.id')
            ->where('orders.status', 'completed')
            ->select(
                'districts.full_name_en as name',
                DB::raw('SUM(orders.total_price) as revenue'),
                DB::raw('COUNT(orders.id) as count')
            )
            ->groupBy('districts.id', 'districts.full_name_en')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        // Ward sales statistics
        $wardSales = Order::join('addresses', 'orders.id', '=', 'addresses.order_id')
            ->join('wards', 'addresses.ward', '=', 'wards.id')
            ->where('orders.status', 'completed')
            ->select(
                'wards.full_name_en as name',
                DB::raw('SUM(orders.total_price) as revenue'),
                DB::raw('COUNT(orders.id) as count')
            )
            ->groupBy('wards.id', 'wards.full_name_en')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
            'totalBlogs',
            'unreadContacts',
            'recentOrders',
            'recentCustomers',
            'monthlySales',
            'recentBlogs',
            'citySales',
            'districtSales',
            'wardSales'
        ));
    }
}
