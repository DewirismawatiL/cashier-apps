<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'pepsi',
            'price' => 20000,
            'image' => 'products/aGbJUJmJt7Fgg8wpiHtdlmITZhm9NDXOXgHHMUmn.png',
            'stock' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
