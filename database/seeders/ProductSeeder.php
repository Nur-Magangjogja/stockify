<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Suppilers;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics  = Categories::where('name', 'Elektronik')->firstOrFail();
        $office       = Categories::where('name', 'Peralatan Kantor')->firstOrFail();
        $furniture    = Categories::where('name', 'Furniture')->firstOrFail();
        $cleaning     = Categories::where('name', 'Alat Kebersihan')->firstOrFail();

        $supplier1 = Suppilers::query()->orderBy('id')->firstOrFail();
        $supplier2 = Suppilers::query()->orderBy('id')->skip(1)->first() ?? $supplier1;

        $products = [
            // Elektronik
            ['category_id' => $electronics->id, 'supplier_id' => $supplier1->id,
             'sku' => 'ELK-LPT001', 'name' => 'Laptop ASUS VivoBook 14',
             'purchase_price' => 8500000, 'selling_price' => 10000000, 'minimum_stock' => 5],
            ['category_id' => $electronics->id, 'supplier_id' => $supplier1->id,
             'sku' => 'ELK-MON001', 'name' => 'Monitor Samsung 24 inch',
             'purchase_price' => 2200000, 'selling_price' => 2750000, 'minimum_stock' => 3],
            ['category_id' => $electronics->id, 'supplier_id' => $supplier1->id,
             'sku' => 'ELK-KBD001', 'name' => 'Keyboard Mechanical Logitech',
             'purchase_price' => 450000, 'selling_price' => 599000, 'minimum_stock' => 5],
            ['category_id' => $electronics->id, 'supplier_id' => $supplier2->id,
             'sku' => 'ELK-USB001', 'name' => 'USB Hub 7 Port',
             'purchase_price' => 85000, 'selling_price' => 120000, 'minimum_stock' => 10],

            // Peralatan Kantor
            ['category_id' => $office->id, 'supplier_id' => $supplier2->id,
             'sku' => 'KNT-PNS001', 'name' => 'Pena Ballpoint Pilot (1 Box)',
             'purchase_price' => 25000, 'selling_price' => 35000, 'minimum_stock' => 10],
            ['category_id' => $office->id, 'supplier_id' => $supplier2->id,
             'sku' => 'KNT-KRT001', 'name' => 'Kertas HVS A4 80gr (1 Rim)',
             'purchase_price' => 45000, 'selling_price' => 58000, 'minimum_stock' => 20],
            ['category_id' => $office->id, 'supplier_id' => $supplier2->id,
             'sku' => 'KNT-STP001', 'name' => 'Stapler Besar Kenko',
             'purchase_price' => 35000, 'selling_price' => 48000, 'minimum_stock' => 5],

            // Furniture
            ['category_id' => $furniture->id, 'supplier_id' => $supplier1->id,
             'sku' => 'FRN-KRS001', 'name' => 'Kursi Kantor Ergonomis',
             'purchase_price' => 850000, 'selling_price' => 1200000, 'minimum_stock' => 2],
            ['category_id' => $furniture->id, 'supplier_id' => $supplier1->id,
             'sku' => 'FRN-MJA001', 'name' => 'Meja Kerja 120x60cm',
             'purchase_price' => 650000, 'selling_price' => 850000, 'minimum_stock' => 2],

            // Kebersihan
            ['category_id' => $cleaning->id, 'supplier_id' => $supplier2->id,
             'sku' => 'KBR-SNT001', 'name' => 'Sabun Cuci Tangan 5L',
             'purchase_price' => 45000, 'selling_price' => 65000, 'minimum_stock' => 10],
        ];

        foreach ($products as $data) {
            Products::firstOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, ['description' => null, 'image' => 'noimage.png'])
            );
        }
    }
}
