<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        for ($i = 1; $i <= 5; $i++) {
            Product::create([
                'name' => "Product $i",
                'desc' => "Description for product $i",
                'price' => rand(10, 100),
                'image' => "product$i.jpg",
                'category_id' => $categories->random()->id,
            ]);
        }
    }
}
