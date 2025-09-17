<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        $cats = Category::all();
        $products = Product::all();

        return view('dashboard', compact('cats', 'products'));
    }

    // --- Categories ---
    public function addCategory(Request $request)
    {
        $request->validate([
            'catname' => 'required|string|max:255',
        ]);

        Category::create(['name' => $request->catname]);

        return Redirect()->back()->with('success', 'Category added.');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['catname' => 'required|string|max:255']);

        $cat = Category::findOrFail($id);
        $cat->name = $request->catname;
        $cat->save();

        return Redirect()->back()->with('success', 'Category updated.');
    }

    public function deleteCategory($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();

        return Redirect()->back()->with('success', 'Category deleted.');
    }

    // --- Products ---
    public function addProduct(Request $request)
    {
        $request->validate([
            'productname' => 'required|string|max:255',
            'productdesc' => 'nullable|string',
            'productprice' => 'required|numeric|min:0',
            'cat_id' => 'required|exists:category_models,id',
        ]);

        Product::create([
            'name' => $request->productname,
            'desc' => $request->productdesc,
            'price' => $request->productprice,
            'cat_id' => $request->cat_id,
        ]);

        return Redirect()->back()->with('success', 'Product added.');
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'productname' => 'required|string|max:255',
            'productdesc' => 'nullable|string',
            'productprice' => 'required|numeric|min:0',
            'cat_id' => 'required|exists:category_models,id',
        ]);

        $p = Product::findOrFail($id);
        $p->update([
            'name' => $request->productname,
            'desc' => $request->productdesc,
            'price' => $request->productprice,
            'cat_id' => $request->cat_id,
        ]);

        return Redirect()->back()->with('success', 'Product updated.');
    }

    public function deleteProduct($id)
    {
        $p = Product::findOrFail($id);
        $p->delete();

        return Redirect()->back()->with('success', 'Product deleted.');
    }
}
