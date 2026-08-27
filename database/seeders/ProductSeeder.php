<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\Category::all();

        $products = [
            // Makanan
            ['category' => 'makanan', 'name' => 'Nasi Goreng Instan', 'sku' => 'MK001', 'buy_price' => 3500, 'sell_price' => 5000, 'stock' => 50, 'unit' => 'pcs'],
            ['category' => 'makanan', 'name' => 'Mie Goreng Sedap', 'sku' => 'MK002', 'buy_price' => 2500, 'sell_price' => 4000, 'stock' => 100, 'unit' => 'pcs'],
            ['category' => 'makanan', 'name' => 'Roti Tawar Sari Roti', 'sku' => 'MK003', 'buy_price' => 12000, 'sell_price' => 15000, 'stock' => 20, 'unit' => 'pack'],

            // Minuman
            ['category' => 'minuman', 'name' => 'Aqua 600ml', 'sku' => 'MN001', 'buy_price' => 3000, 'sell_price' => 4000, 'stock' => 48, 'unit' => 'pcs'],
            ['category' => 'minuman', 'name' => 'Coca Cola 330ml', 'sku' => 'MN002', 'buy_price' => 4000, 'sell_price' => 6000, 'stock' => 24, 'unit' => 'pcs'],
            ['category' => 'minuman', 'name' => 'Teh Botol Sosro', 'sku' => 'MN003', 'buy_price' => 3500, 'sell_price' => 5000, 'stock' => 36, 'unit' => 'pcs'],

            // Snack
            ['category' => 'snack', 'name' => 'Chitato Sapi Panggang', 'sku' => 'SN001', 'buy_price' => 8000, 'sell_price' => 11000, 'stock' => 30, 'unit' => 'pcs'],
            ['category' => 'snack', 'name' => 'Taro Net', 'sku' => 'SN002', 'buy_price' => 2500, 'sell_price' => 4000, 'stock' => 40, 'unit' => 'pcs'],
            ['category' => 'snack', 'name' => 'Oreo Vanilla', 'sku' => 'SN003', 'buy_price' => 6000, 'sell_price' => 8500, 'stock' => 25, 'unit' => 'pcs'],

            // Kebersihan
            ['category' => 'kebersihan', 'name' => 'Rinso Anti Noda', 'sku' => 'KB001', 'buy_price' => 15000, 'sell_price' => 18000, 'stock' => 15, 'unit' => 'pcs'],
            ['category' => 'kebersihan', 'name' => 'Sunlight 755ml', 'sku' => 'KB002', 'buy_price' => 8000, 'sell_price' => 10000, 'stock' => 20, 'unit' => 'pcs'],

            // Kebutuhan Dapur
            ['category' => 'kebutuhan-dapur', 'name' => 'Minyak Goreng Bimoli 1L', 'sku' => 'KD001', 'buy_price' => 18000, 'sell_price' => 22000, 'stock' => 25, 'unit' => 'pcs'],
            ['category' => 'kebutuhan-dapur', 'name' => 'Gula Pasir 1kg', 'sku' => 'KD002', 'buy_price' => 14000, 'sell_price' => 17000, 'stock' => 30, 'unit' => 'pcs'],
            ['category' => 'kebutuhan-dapur', 'name' => 'Gas Elpiji 3kg', 'sku' => 'KD003', 'buy_price' => 10000, 'sell_price' => 20000, 'stock' => 10, 'unit' => 'pcs'],
        ];

        foreach ($products as $product) {
            $category = $categories->firstWhere('slug', $product['category']);
            if ($category) {
                \App\Models\Product::updateOrCreate(
                    ['sku' => $product['sku']],
                    [
                        'category_id' => $category->id,
                        'name' => $product['name'],
                        'buy_price' => $product['buy_price'],
                        'sell_price' => $product['sell_price'],
                        'stock' => $product['stock'],
                        'unit' => $product['unit'],
                        'min_stock' => 5,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
