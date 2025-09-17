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

    // -----------------
    // Categories
    // -----------------
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

    // -----------------
    // Products
    // -----------------
    public function addProduct(Request $request)
    {
        $request->validate([
            'productname'  => 'required|string|max:255',
            'productdesc'  => 'nullable|string',
            'productprice' => 'required|numeric|min:0',
            'cat_id'       => 'required|exists:categories,id',
            'imgpro'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $request->productname,
            'desc' => $request->productdesc,
            'price' => $request->productprice,
            'cat_id' => $request->cat_id,
        ];

        if ($request->hasFile('imgpro')) {
            $imageName = time() . '.' . $request->imgpro->getClientOriginalExtension();
            $request->imgpro->move(public_path('images'), $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        Product::create($data);

        return Redirect()->back()->with('success', 'Product added.');
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'productname'  => 'required|string|max:255',
            'productdesc'  => 'nullable|string',
            'productprice' => 'required|numeric|min:0',
            'cat_id'       => 'required|exists:categories,id',
            'imgpro'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $p = Product::findOrFail($id);

        $p->name = $request->productname;
        $p->desc = $request->productdesc;
        $p->price = $request->productprice;
        $p->cat_id = $request->cat_id;

        if ($request->hasFile('imgpro')) {
            if ($p->image && file_exists(public_path($p->image))) {
                unlink(public_path($p->image));
            }

            $imageName = time() . '.' . $request->imgpro->getClientOriginalExtension();
            $request->imgpro->move(public_path('images'), $imageName);
            $p->image = 'images/' . $imageName;
        }

        $p->save();

        return Redirect()->back()->with('success', 'Product updated.');
    }

    public function deleteProduct($id)
    {
        $p = Product::findOrFail($id);

        if ($p->image && file_exists(public_path($p->image))) {
            unlink(public_path($p->image));
        }

        $p->delete();

        return Redirect()->back()->with('success', 'Product deleted.');
    }
}
