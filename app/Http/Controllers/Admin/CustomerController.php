<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Address;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $orders = Order::all();
        $address = Address::all();
        return view('admin.customers.index', compact('customers', 'orders', 'address'));
    }
}
