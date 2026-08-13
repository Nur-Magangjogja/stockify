<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PerformanceCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mulai membuat dummy data Categories (100) dan Products (1000)...');

        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // 1. Generate 100 Categories
        $categoriesData = [];
        for ($i = 1; $i <= 100; $i++) {
            $categoriesData[] = [
                'name' => $faker->unique()->words(rand(1, 3), true) . ' Category',
                'description' => $faker->sentence(10),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        
        DB::table('categories')->insert($categoriesData);
        $this->command->info('100 Categories berhasil ditambahkan.');

        // Get created Category IDs
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        
        // Get existing Supplier IDs (Assume there are some, if not, fallback to 1)
        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();
        if (empty($supplierIds)) {
            $this->command->error('Tabel suppliers kosong! Harap jalankan SupplierSeeder terlebih dahulu.');
            return;
        }

        // 2. Generate 1000 Products
        $productsData = [];
        $totalProducts = 1000;
        
        $this->command->getOutput()->progressStart($totalProducts);

        for ($i = 1; $i <= $totalProducts; $i++) {
            $purchasePrice = $faker->numberBetween(10, 500) * 1000; // 10.000 sampai 500.000
            $sellingPrice = $purchasePrice + ($purchasePrice * $faker->randomFloat(2, 0.1, 0.5)); // Untung 10%-50%
            
            $productsData[] = [
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'supplier_id' => $supplierIds[array_rand($supplierIds)],
                'name' => 'Product ' . ucwords($faker->unique()->words(rand(2, 4), true)),
                'sku' => strtoupper($faker->unique()->bothify('PRD-????-####')),
                'description' => $faker->paragraph(),
                'purchase_price' => $purchasePrice,
                'selling_price' => $sellingPrice,
                'image' => 'noimage.png',
                'minimum_stock' => $faker->numberBetween(5, 50),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Chunk insert per 500 items
            if ($i % 500 == 0) {
                DB::table('products')->insert($productsData);
                $productsData = []; // Reset array
                $this->command->getOutput()->progressAdvance(500);
            }
        }

        // Insert remaining if any (walau dalam kasus ini pas 1000, 500 * 2)
        if (!empty($productsData)) {
            DB::table('products')->insert($productsData);
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('1000 Products berhasil ditambahkan.');
    }
}
