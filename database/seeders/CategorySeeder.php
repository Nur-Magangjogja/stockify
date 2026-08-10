<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik',        'description' => 'Perangkat elektronik, gadget, dan aksesorisnya'],
            ['name' => 'Peralatan Kantor',  'description' => 'Alat tulis, printer, dan perlengkapan kantor'],
            ['name' => 'Furniture',         'description' => 'Meja, kursi, lemari, dan perlengkapan interior'],
            ['name' => 'Alat Kebersihan',   'description' => 'Produk kebersihan dan sanitasi'],
            ['name' => 'Makanan & Minuman', 'description' => 'Produk konsumsi makanan dan minuman'],
            ['name' => 'Pakaian',           'description' => 'Pakaian, fashion, dan aksesoris'],
            ['name' => 'Spare Part',        'description' => 'Suku cadang mesin dan kendaraan'],
        ];

        foreach ($categories as $data) {
            Categories::firstOrCreate(
                ['name' => $data['name']],
                ['description' => $data['description']]
            );
        }
    }
}
