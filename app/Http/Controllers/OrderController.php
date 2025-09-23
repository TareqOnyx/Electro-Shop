<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class OrderController extends Controller
{
    // عرض الطلبات الخاصة بالمستخدم
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->get();

        return view('orders.index', compact('orders'));
    }

    // إنشاء طلب جديد (من السلة)
    public function store(Request $request)
    {
        try {
            // جلب السلة
            $cartItems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'السلة فارغة');
            }

            // تحقق من المخزون + احسب الإجمالي
            $total = 0;
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->back()->with('error', "المنتج {$item->product->name} لا يحتوي على الكمية المطلوبة");
                }
                $total += $item->product->price * $item->quantity;
            }

            // إنشاء الطلب
            $order = Order::create([
                'user_id' => auth()->id(),
                'total'   => $total,
                'status'  => 'pending',
            ]);

            // تحديث المخزون + إنشاء عناصر الطلب
            foreach ($cartItems as $item) {
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item->quantity,
                    'price'      => $product->price,
                ]);
            }

            // مسح السلة
            Cart::where('user_id', auth()->id())->delete();

            return redirect()->route('orders.index')->with('success', 'تم إنشاء الطلب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في إنشاء الطلب: ' . $e->getMessage());
        }
    }

    // تحديث حالة الطلب (للمستخدم نفسه)
    public function updateStatus(Request $request, $id)
    {
        try {
            $order = Order::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $request->validate([
                'status' => 'required|string|in:pending,confirmed,delivered,canceled',
            ]);

            $order->update([
                'status' => $request->status,
            ]);

            return redirect()->route('orders.index')->with('success', 'تم تحديث حالة الطلب');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'الطلب غير موجود');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة الطلب: ' . $e->getMessage());
        }
    }

    // صفحة الدفع
    public function checkout()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout', compact('cartItems', 'total'));
    }
}
