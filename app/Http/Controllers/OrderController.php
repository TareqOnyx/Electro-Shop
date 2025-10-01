<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Area;
use App\Models\OrderStatusHistory;

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
    $request->validate([
        'area_id' => 'required|exists:areas,id',
        'address' => 'required|string|max:255',
    ]);

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

        // Create order with area_id and address
        $order = Order::create([
            'user_id'  => auth()->id(),
            'total'    => $total,
            'status'   => 'pending',
            'area_id'  => $request->area_id,
            'address'  => $request->address,
        ]);

        // Create order items
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

        return redirect()->route('checkout')->with('success', 'Order placed successfully');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to place order: ' . $e->getMessage());
    }
}


public function updateStatus(Request $request, $id)
{
    try {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:pending,confirmed,delivered,canceled',
        ]);

        // Update order status
        $order->update([
            'status' => $request->status,
        ]);

        // Log history
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status'   => $request->status,
            'admin_id' => auth()->id(), // assumes only admins use this route
        ]);

        return redirect()->route('orders.index')->with('success', 'Order status updated');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->back()->with('error', 'Order not found');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to update order: ' . $e->getMessage());
    }
}

    // Show all orders (admin)
public function adminIndex()
{
    $orders = Order::with('items.product', 'user', 'area', 'statusHistories.admin')->get();
    return view('orders', compact('orders'));
}

// Update order status (admin)
public function updateStatusAdmin(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|string|in:pending,confirmed,delivered,canceled',
    ]);

    $order->update(['status' => $request->status]);

            OrderStatusHistory::create([
            'order_id' => $order->id,
            'status'   => $request->status,
            'admin_id' => auth()->id(), // assumes only admins use this route
        ]);

    return redirect()->route('orders.index')->with('success', 'Order status updated.');
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

    $areas = Area::all(); // <-- load areas here

    return view('checkout', compact('cartItems', 'total', 'areas'));
}
}
