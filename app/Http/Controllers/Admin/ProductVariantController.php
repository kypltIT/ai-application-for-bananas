<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductVariantController extends Controller
{
    public function index($id)
    {
        $product = Product::findOrFail($id);
        $productVariants = ProductVariant::where('product_id', $product->id)->get();
        return view('admin.products.product-variants.index', compact('productVariants', 'product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $productVariant = ProductVariant::create($validated);
        $productVariant->generateSku();
        return redirect()->route('admin.products.product-variants.index', $product->id)->with('success', 'Product variant created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'sku' => 'required',
        ]);

        $productVariant = ProductVariant::findOrFail($id);
        $productVariant->update($validated);
        return redirect()->route('admin.products.product-variants.index', $productVariant->product_id)->with('success', 'Product variant updated successfully.');
    }

    public function destroy($id)
    {
        $productVariant = ProductVariant::findOrFail($id);
        $productVariant->delete();
        return redirect()->route('admin.products.product-variants.index', $productVariant->product_id)->with('success', 'Product variant deleted successfully.');
    }

    public function createImage(Request $request)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|uuid|exists:product_variants,id',
            // 'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=366,max_height=400',4
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $productVariant = ProductVariant::findOrFail($validated['product_variant_id']);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('product-variants', 'public');

                ProductVariantImage::create([
                    'product_variant_id' => $productVariant->id,
                    'image_path' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin.products.product-variants.index', $productVariant->product_id)
            ->with('success', 'Product variant images created successfully.');
    }

    public function destroyImage($productVariantImageId)
    {
        $productVariantImage = ProductVariantImage::findOrFail($productVariantImageId);

        if ($productVariantImage->image_path) {
            $filePath = public_path('storage/' . $productVariantImage->image_path);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $productVariantImage->delete();
        return response()->json(['success' => true]);
    }
}
