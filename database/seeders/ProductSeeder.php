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
        Product::query()->create([
            'name' => 'Lord of the Rings',
            'description' => 'One Ring to rule them all, One Ring to find them.',
            'price' => 120.00,
            'image' => 'https://m.media-amazon.com/images/I/7125+5E40JL.jpg',
        ]);
    }
}
