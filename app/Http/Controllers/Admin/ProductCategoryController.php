<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::all();
        return view('admin.product-categories.index', compact('productCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required',
            'description' => 'required|string|max:255',
        ]);

        // Lưu ảnh vào thư mục storage
        $imageName = time() . '_' . $request->name . '.' . $request->image->getClientOriginalExtension();
        $path = $request->file('image')->storeAs('product-categories', $imageName, 'public');

        // Lưu vào database
        ProductCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => 'product-categories/' . $imageName,
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Product category created successfully');
    }


    // Updated the update method to define $imageName before using it to avoid undefined variable error.
    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable',
            'description' => 'required|string|max:255',
        ]);

        $imageName = $productCategory->image; // Default to existing image

        if ($request->hasFile('image')) {
            if ($productCategory->image) {
                Storage::disk('public')->delete('product-categories/' . $productCategory->image);
            }
            $imageName = time() . '_' . $request->name . '.' . $request->image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('product-categories', $imageName, 'public');
        }

        $productCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => 'product-categories/' . $imageName,
        ]);

        return redirect()->route('admin.product-categories.index')->with('success', 'Product category updated successfully');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->image) {
            Storage::disk('public')->delete('product-categories/' . $productCategory->image);
        }

        $productCategory->delete();
        return redirect()->route('admin.product-categories.index')->with('success', 'Product category deleted successfully');
    }
}
