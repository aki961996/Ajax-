<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  Product::factory()->count(10)->create(); // if you have a factory

        Product::insert([
        [
            'title' => 'Product 1',
            'description' => 'Description for Product 1',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'title' => 'Product 2',
            'description' => 'Description for Product 2',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        // Add more if needed
    ]);
    }
}
