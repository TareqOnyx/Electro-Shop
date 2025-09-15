<?php

namespace App\Http\Controllers;

use App\Models\Category_Model;
use App\Models\Product_Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Product_Controller extends Controller
{
    public function ShowProductPage(){
        $categories = Category_Model::all();
        $products = Product_Model::all();
        return view("products", compact("products","categories"));
    }

    public function AddProduct(Request $req){
        $p = new Product_Model();
        $p->name = $req->productname;
        $p->desc = $req->productdesc;
        $p->price = $req->productprice;
        $p->cat_id = $req->cat_id;

        $image = $req->imgpro;
        if ($image) {
            $image = $req->file('imgpro');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $p->image = 'images/' . $imageName;
        }

        $p->save();
        return redirect()->back()->with("message", "Product added successfully");
    }

    public function DeleteProduct($id){
        $pr = Product_Model::find($id);

        // Delete image file if exists
        if ($pr->image && file_exists(public_path($pr->image))) {
            unlink(public_path($pr->image));
        }

        $pr->delete();
        return Redirect()->back();
    }

    // ✅ New Update Method
    public function UpdateProduct(Request $req, $id){
        $p = Product_Model::find($id);
        $p->name = $req->productname;
        $p->desc = $req->productdesc;
        $p->price = $req->productprice;
        $p->cat_id = $req->cat_id;

        $image = $req->imgpro;
        if ($image) {
            // Delete old image if exists
            if ($p->image && file_exists(public_path($p->image))) {
                unlink(public_path($p->image));
            }

            $image = $req->file('imgpro');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $p->image = 'images/' . $imageName;
        }

        $p->save();
        return redirect()->back()->with("message", "Product updated successfully");
    }
}
