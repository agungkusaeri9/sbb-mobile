<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Electronics',
        ]);
        Category::create([
            'name' => 'Books',
        ]);

        Category::create([
            'name' => 'Clothing',
        ]);
        Category::create([
            'name' => 'Home & Kitchen',
        ]);
        Category::create([
            'name' => 'Sports & Outdoors',
        ]);
        Category::create([
            'name' => 'Toys & Games',
        ]);
        Category::create([
            'name' => 'Health & Personal Care',
        ]);
        Category::create([
            'name' => 'Beauty & Personal Care',
        ]);
        Category::create([
            'name' => 'Automotive',
        ]);
        Category::create([
            'name' => 'Grocery & Gourmet Food',
        ]);
    }
}
