<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductCategory;
use App\Models\FrontendSetting;
use App\Models\Testimonial;
use App\Models\Blog;

if (!function_exists('getCart')) {
    function getCart()
    {
        return Cart::where('session_id', session()->getId())->first();
    }
}

if (!function_exists('getCartItems')) {
    function getCartItems()
    {
        $cart = getCart();
        if (!$cart) {
            return collect();
        }
        return CartItem::where('cart_id', $cart->id)->get();
    }
}

if (!function_exists('countCartItems')) {
    function countCartItems()
    {
        return getCartItems()->count();
    }
}

if (!function_exists('getCategories')) {
    function getCategories()
    {
        return ProductCategory::all();
    }
}

if (!function_exists('getCartTotal')) {
    function getCartTotal()
    {
        $total = 0;
        foreach (getCartItems() as $cartItem) {
            $total += $cartItem->quantity * $cartItem->productVariant->price;
        }
        return $total;
    }
}

if (!function_exists('getCartId')) {
    function getCartId()
    {
        $cart = getCart();
        if (!$cart) {
            return null;
        }
        return $cart->id;
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key)
    {
        $setting = FrontendSetting::where('key', $key)->first();
        return $setting ? $setting->value : null;
    }
}

if (!function_exists('getTestimonials')) {
    function getTestimonials()
    {
        return Testimonial::all();
    }
}

if (!function_exists('getBlogs')) {
    function getBlogs()
    {
        return Blog::orderBy('created_at', 'desc')->limit(3)->get();
    }
}

if (!function_exists('getPrimaryColor')) {
    function getPrimaryColor()
    {
        return env('WEB_PRIMARY_COLOR');
    }
}

if (!function_exists('getSecondaryColor')) {
    function getSecondaryColor()
    {
        return env('WEB_SECONDARY_COLOR');
    }
}
