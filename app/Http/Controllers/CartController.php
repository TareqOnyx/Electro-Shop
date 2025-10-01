<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    //  جلب عناصر السلة
    public function index() 
    {
        $carts = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();
    }

    //  إضافة عنصر للسلة
    public function store(Request $request)
    {
        try {
            $valid = $request->validate([
                'user_id' => 'required|exists:products,id',
                'product_id' => 'required|exists:products,id',
                'quantity'   => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($valid['product_id']);

            $cart = Cart::create([
                'user_id'    => auth()->id(),
                'product_id' => $valid['product_id'],
                'quantity'   => $valid['quantity'],
            ]);

            return redirect()->back()->with([
                'success' => true,
                'message' => 'تمت إضافة المنتج إلى السلة',
                'cart'    => $cart->load('product')
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error'   => 'فشل في إضافة العنصر للسلة',
                'details' => $e->getMessage()
            ]);
        }
    }

    //  تعديل عنصر بالسلة
    public function update(Request $request, $id)
    {
        try {
            $cart = Cart::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

            $valid = $request->validate([
                'quantity' => 'sometimes|integer|min:1',
            ]);

            $cart->update($valid);

            return redirect()->back()->with([
                'success' => true,
                'message' => 'تم تعديل الكمية',
                'cart' => $cart->load('product')
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with([
                'error' => 'العنصر غير موجود في السلة'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error'   => 'فشل في تعديل العنصر',
                'details' => $e->getMessage()
            ]);
        }
    }

    //  حذف عنصر من السلة
    public function destroy($id)
    {
        try {
            $cart = Cart::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();

            $cart->delete();

            return redirect()->back()->with([
                'success' => true,
                'message' => 'تم حذف العنصر من السلة'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with([
                'error' => 'العنصر غير موجود في السلة'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error'   => 'فشل في حذف العنصر من السلة',
                'details' => $e->getMessage()
            ]);
        }
    }
}
