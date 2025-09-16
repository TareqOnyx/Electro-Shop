<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;

class Category_Controller extends Controller
{
    // Show
    public function ShowCategoryPage()
    {
        $cats = Category::all();
        return view("categories", compact('cats'));
    }

    // Add
    public function AddCategory(Request $req)
    {
        $req->validate([
            'catname' => 'required|string|max:255|unique:categories,name',
        ]);

        $cat = new Category();
        $cat->name = $req->catname;
        $cat->save();

        return Redirect()->back()->with('message', 'Category added successfully!');
    }

    // Update
    public function UpdatePage($id)
    {
        $catupdate = Category::findOrFail($id);
        return view("updatecat", compact("catupdate"));
    }

    public function UpdateCat(Request $req, $id)
    {
        $req->validate([
            'catname' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $catupdate = Category::findOrFail($id);
        $catupdate->name = $req->catname;
        $catupdate->save();

        return redirect()->back()->with('message', 'Category updated successfully!');
    }

    // Delete
    public function DeleteCategory($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();

        return Redirect()->back()->with('message', 'Category deleted successfully!');
    }
}
