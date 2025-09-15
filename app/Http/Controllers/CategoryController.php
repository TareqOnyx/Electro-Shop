<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category_Model;
use Illuminate\Support\Facades\Redirect;

class Category_Controller extends Controller
{
    //Show
    public function ShowCategoryPage(){
        $cats = Category_Model::all();
        return view("categories", compact('cats'));
    }


    //Add
    public function AddCategory(Request $req){
        $cat = new Category_Model();
        $cat->name = $req->catname;

        // Handle image upload
        $image = $req->imgcat; // imgcat is the input name in form
        if ($image) {
            $image = $req->file('imgpro');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $cat->image = 'images/' . $imageName;
        }

        $cat->save();
        return Redirect()->back();
    }


//Update
    public function UpdatePage($id){
        $catupdate = Category_Model::find($id);
        return view("updatecat", compact("catupdate"));
    }

    public function UpdateCat(Request $req, $id){
        $catupdate = Category_Model::find($id);
        $catupdate->name = $req->catname;

        // Handle image upload
        $image = $req->imgpro;
        if ($image) {
            // Delete old image if exists
            if ($catupdate->image && file_exists(public_path($catupdate->image))) {
                unlink(public_path($catupdate->image));
            }

            $image = $req->file('imgpro');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $catupdate->image = 'images/' . $imageName;
        }

        $catupdate->save();
        return redirect()->back();
    }

    // Delete
        public function DeleteCategory($id){
        $cat = Category_Model::find($id);

        // Delete the image file if exists
        if ($cat->image && file_exists(public_path($cat->image))) {
            unlink(public_path($cat->image));
        }

        $cat->delete(); 
        return Redirect()->back();
    }
}
