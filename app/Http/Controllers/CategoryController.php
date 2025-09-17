<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    // GET /categories
    public function index()
    {
        $cats = Category::all();
        return view("dashboard", compact('cats')); // now $cats is available in dashboard
    }

    // POST /categories
    public function store(Request $request)
    {
        $request->validate([
            'catname' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->catname
        ]);

        return redirect()->route('categories.index');
    }

    // GET /categories/{id}/edit
    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        return view('updatecat', compact('cat'));
    }

    // PUT /categories/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'catname' => 'required|string|max:255'
        ]);

        $cat = Category::findOrFail($id);
        $cat->name = $request->catname;
        $cat->save();

        return redirect()->route('categories.index');
    }

    // DELETE /categories/{id}
    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();

        return redirect()->route('categories.index');
    }
}
