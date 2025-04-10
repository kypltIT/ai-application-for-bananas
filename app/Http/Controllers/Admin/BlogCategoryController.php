<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::all();
        return view('admin.blog-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request['slug'] = BlogCategory::generateSlug($request->name);

        BlogCategory::create($request->all());
        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category created successfully');
    }

    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request['slug'] = BlogCategory::generateSlug($request->name);

        $category->update($request->all());
        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category updated successfully');
    }

    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category deleted successfully');
    }
}
