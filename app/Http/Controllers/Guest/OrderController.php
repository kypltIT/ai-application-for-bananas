<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\District;
use App\Models\Ward;
use App\Models\Cart;

class OrderController extends Controller
{
    public function index()
    {
        $cities = City::all();
        $districts = District::all();
        $wards = Ward::all();
        return view('guest.orders.index', compact('cities', 'districts', 'wards'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required|exists:cities,id',
            'district' => 'required|exists:districts,id',
            'ward' => 'required|exists:wards,id',
            'order_note' => 'nullable',
            'payment_method' => 'required|in:COD,Bank Transfer',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $customer = Customer::where('phone', $request->phone)->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
        }


        $order = Order::create([
            'customer_id' => $customer->id,
            'total_price' => getCartTotal(),
            'status' => 'ordered',
            'order_code' => Order::generateOrderCode(),
            'order_note' => $request->order_note,
        ]);


        $cartItems = CartItem::where('cart_id', $request->cart_id)->get();
        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
            ]);
        }

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'total_price' => getCartTotal(),
            'status' => 'pending',
            'transaction_code' => Transaction::generateTransactionCode(),
            'payment_method' => $request->payment_method,
        ]);

        $address = Address::create([
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'district' => $request->district,
            'ward' => $request->ward,
        ]);

        $cart = Cart::find($request->cart_id);
        $cart->delete();

        if ($request->payment_method == 'COD') {
            return redirect()->route('orders.success', $order->order_code)->with('success', 'Order placed successfully');
        } else {
            return redirect()->route('orders.checkout', $order->id)->with('success', 'Order placed successfully');
        }
    }

    public function showSuccess($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        return view('guest.orders.success', compact('order'));
    }

    public function showCheckout($id)
    {
        $order = Order::find($id);
        $transaction = Transaction::where('order_id', $order->id)->first();
        $qrCodeUrl = $this->getImageQRCode($order->order_code, $order->total_price);
        return view('guest.orders.checkout', compact('order', 'transaction', 'qrCodeUrl'));
    }

    public function getImageQRCode($orderCode, $totalPrice)
    {

        $bank = "MBBank";
        $account = "79678988889999";

        $qrCodeUrl = "https://qr.sepay.vn/img?acc=$account&bank=$bank&amount=" . ($totalPrice * 1) . "&des=$orderCode&template=TEMPLATE&download=DOWNLOAD";

        return $qrCodeUrl;
    }

    public function getPaymentStatus($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->first();
        $order = Order::find($orderId);
        if ($transaction->status === 'paid') {
            return response()->json(['status' => 'success', 'redirect' => route('orders.success', $order->order_code)]);
        }

        return response()->json(['status' => $transaction->status]);
    }
}
