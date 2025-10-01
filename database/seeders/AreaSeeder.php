<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Downtown', 'shipping' => 5.00],
            ['name' => 'Uptown',   'shipping' => 7.50],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
