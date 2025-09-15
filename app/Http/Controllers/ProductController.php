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
        $image = $req-> imgpro;
        if ($image) {
            $image = $req->file('imgpro');
            $imageName = time() . '.' . $req->file('imgpro')->getClientOriginalExtension();
            $req->imgpro->move('images', $imageName);
            $p->image = 'images/' . $imageName;
            $p->save();
            return redirect()->back()->with("message", "product added successfully");
        }
        else{
             return redirect()->back()->with("message1", "Error");
        }
       
    }

    public function DeleteProduct($id){
        $pr = Product_Model::find($id);
        $pr->delete();
        return Redirect()->back();

    }
}
