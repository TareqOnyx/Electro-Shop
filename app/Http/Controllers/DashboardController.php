<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Area;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        $cats = Category::all();
        $products = Product::all();
        $areas = Area::all();

        return view('dashboard', compact('cats', 'products', 'areas'));
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
            'name'        => 'required|string|max:255',
            'desc'        => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'imgpro'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'desc', 'price', 'category_id']);

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
            'name'        => 'required|string|max:255',
            'desc'        => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'imgpro'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $data = $request->only(['name', 'desc', 'price', 'category_id']);

        if ($request->hasFile('imgpro')) {
            // Delete old image
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $imageName = time() . '.' . $request->imgpro->getClientOriginalExtension();
            $request->imgpro->move(public_path('images'), $imageName);
            $data['image'] = 'images/' . $imageName;
        }

        $product->update($data);

        return Redirect()->back()->with('success', 'Product updated.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return Redirect()->back()->with('success', 'Product deleted.');
    }

    // --- Areas ---
public function addArea(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    Area::create(['name' => $request->name]);

    return Redirect()->back()->with('success', 'Area added.');
}

public function updateArea(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:50',
    ]);

    $area = Area::findOrFail($id);
    $area->name = $request->name;
    $area->save();

    return Redirect()->back()->with('success', 'Area updated.');
}

public function deleteArea($id)
{
    $area = Area::findOrFail($id);
    $area->delete();

    return Redirect()->back()->with('success', 'Area deleted.');
}

}
