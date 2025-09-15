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

        $catupdate->save();
        return redirect()->back();
    }

    // Delete
        public function DeleteCategory($id){
        $cat = Category_Model::find($id);

        $cat->delete(); 
        return Redirect()->back();
    }
}
