<?php

namespace Database\Seeders;

use App\Models\Product;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Product 1',
            'purchase_price_per_unit' => 10.00,
            'selling_price_per_unit' => 15.00,
            'available_stock' => 100,
            'tax_percentage' => 5,
        ]);

        Product::create([
            'name' => 'Product 2',
            'purchase_price_per_unit' => 15.00,
            'selling_price_per_unit' => 20.00,
            'available_stock' => 150,
            'tax_percentage' => 8,
        ]);
    }
}
