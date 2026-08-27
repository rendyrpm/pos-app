<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'description' => 'Produk makanan'],
            ['name' => 'Minuman', 'slug' => 'minuman', 'description' => 'Produk minuman'],
            ['name' => 'Snack', 'slug' => 'snack', 'description' => 'Snack dan cemilan'],
            ['name' => 'Kebersihan', 'slug' => 'kebersihan', 'description' => 'Produk kebersihan'],
            ['name' => 'Kebutuhan Dapur', 'slug' => 'kebutuhan-dapur', 'description' => 'Kebutuhan dapur'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
