<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 1)->first(); // admin
        $product = Product::first();

        if ($user && $product) {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }
    }
}
