<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $cart = Cart::where('session_id', $request->session()->getId())->first();
        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $request->session()->getId(),
            ]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json($cartItem);
    }

    public function removeFromCart(Request $request)
    {
        $cartItem = CartItem::where('id', $request->cart_item_id)->first();
        $cartItem->delete();
        return response()->json(['success' => true]);
    }

    public function getCart(Request $request)
    {
        $cart = Cart::where('session_id', $request->session()->getId())->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        return response()->json($cartItems);
    }

    public function getCartItemsHtml()
    {
        $html = view('layouts.guest.components.cart-items')->render();
        return response()->json(['html' => $html]);
    }

    public function getCountCartItems()
    {
        $html = view('layouts.guest.components.count-cart-items')->render();
        return response()->json(['html' => $html]);
    }

    public function getCartTotalPrice()
    {
        $html = view('layouts.guest.components.cart-total-price')->render();
        return response()->json(['html' => $html]);
    }
}
