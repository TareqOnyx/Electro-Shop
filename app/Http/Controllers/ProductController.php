<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }


    // POST /products
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:60',
            'desc'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'cat_id' => 'required|exists:categories,id',
            'imgpro' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = new Product($request->only('name', 'desc', 'price', 'cat_id'));

        if ($request->hasFile('imgpro')) {
            $imageName = time() . '.' . $request->imgpro->getClientOriginalExtension();
            $request->imgpro->move(public_path('images'), $imageName);
            $product->image = 'images/' . $imageName;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    // GET /products/{id}
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }


    // PUT/PATCH /products/{id}
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'desc'   => 'required|string',
            'price'  => 'required|numeric|min:0',
            'cat_id' => 'required|exists:categories,id',
            'imgpro' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product->update($request->only('name', 'desc', 'price', 'cat_id'));

        if ($request->hasFile('imgpro')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $imageName = time() . '.' . $request->imgpro->getClientOriginalExtension();
            $request->imgpro->move(public_path('images'), $imageName);
            $product->image = 'images/' . $imageName;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // DELETE /products/{id}
    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
