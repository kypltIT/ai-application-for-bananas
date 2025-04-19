<?php

/**
 * Updated: Changed revenue calculation to use orders.total_price instead of order_items
 * Features:
 * - Calculate revenue from orders.total_price
 * - Calculate total orders by product category
 * - Filter by date range and categories
 * - Display statistical data with proper category names
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return view('admin.statistics.index', compact('categories'));
    }

    public function getStatistics(Request $request)
    {
        $query = Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('orders.status', 'completed');

        // Date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('orders.created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Category filter
        if ($request->category) {
            $query->where('products.product_category_id', $request->category);
        }

        // Get total orders by category
        $ordersByCategory = $this->getOrdersByCategory($request);
        $totalOrders = array_sum(array_values($ordersByCategory));

        // Calculate total revenue
        $revenueQuery = clone $query;
        $totalRevenue = $revenueQuery->select(DB::raw('SUM(DISTINCT orders.total_price) as total_revenue'))
            ->first()
            ->total_revenue ?? 0;

        $averageRevenue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $statistics = [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'orders_by_category' => $ordersByCategory,
            'average_revenue' => $averageRevenue,
            'revenue_by_category' => $this->getRevenueByCategory($request),
            'revenue_by_month' => $this->getRevenueByMonth($request)
        ];

        return response()->json($statistics);
    }

    private function getOrdersByCategory(Request $request)
    {
        return Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->where('orders.status', 'completed')
            ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                $q->whereBetween('orders.created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            })
            ->select(
                'product_categories.name as category_name',
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('product_categories.id', 'product_categories.name')
            ->pluck('order_count', 'category_name')
            ->toArray();
    }

    private function getRevenueByCategory(Request $request)
    {
        return Order::query()
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->where('orders.status', 'completed')
            ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                $q->whereBetween('orders.created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            })
            ->select(
                'product_categories.name as category_name',
                DB::raw('SUM(orders.total_price) as revenue')
            )
            ->groupBy('product_categories.id', 'product_categories.name')
            ->pluck('revenue', 'category_name')
            ->toArray();
    }

    private function getRevenueByMonth(Request $request)
    {
        return Order::query()
            ->where('status', 'completed')
            ->when($request->start_date && $request->end_date, function ($q) use ($request) {
                $q->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            })
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();
    }
}
