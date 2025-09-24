<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;

class OrderController extends Controller
{
    // Show user orders
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->get();

        return view('orders.index', compact('orders'));
    }

    // Create a new order (from cart)
    public function store(Request $request)
    {
        try {
            // Get cart
            $cartItems = Cart::with('product')
                ->where('user_id', auth()->id())
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Your cart is empty');
            }

            // Calculate total
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->product->price * $item->quantity;
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total'   => $total,
                'status'  => 'pending',
            ]);

            // Create order items (no stock update)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product->id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            return redirect()->route('orders.index')->with('success', 'Order placed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    // Update order status (user only)
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

            return redirect()->route('orders.index')->with('success', 'Order status updated');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Order not found');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    // Checkout page
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
