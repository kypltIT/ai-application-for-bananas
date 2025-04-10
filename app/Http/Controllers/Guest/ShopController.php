<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('productVariants.productVariantImages');

        // Kiểm tra nếu tham số category[] có trong request và là mảng
        if ($request->has('category') && is_array($request->input('category'))) {
            $query->whereIn('product_category_id', $request->input('category'));
        }

        // Kiểm tra nếu có tham số tìm kiếm
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Lấy sản phẩm với phân trang
        $products = $query->paginate(12);

        // Lấy tất cả danh mục sản phẩm
        $productCategories = ProductCategory::all();

        return view('guest.shops.index', compact('products', 'productCategories'));
    }


    public function show($id)
    {
        $product = Product::find($id);
        $productVariants = $product->productVariants;
        $relatedProducts = Product::where('product_category_id', $product->product_category_id)->whereHas('productVariants.productVariantImages')->get();
        return view('guest.shops.show', compact('product', 'productVariants', 'relatedProducts'));
    }

    public function getVariantOfProduct($productId, $variantId)
    {
        $productVariant = ProductVariant::where('product_id', $productId)->where('id', $variantId)->first();
        return response()->json($productVariant);
    }

    public function getProductVariantImages($variantId)
    {
        $productVariant = ProductVariant::where('id', $variantId)->first();
        return response()->json($productVariant->productVariantImages);
    }
}
