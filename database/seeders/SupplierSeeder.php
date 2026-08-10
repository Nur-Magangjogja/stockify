<?php

namespace Database\Seeders;

use App\Models\Suppilers;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'PT Teknologi Maju Bersama',
                'email'          => 'procurement@tmb.co.id',
                'phone'          => '021-5551234',
                'address'        => 'Jl. Sudirman No. 45, Jakarta Pusat',
            ],
            [
                'name'           => 'CV Karya Mandiri',
                'email'          => 'info@karyamandiri.com',
                'phone'          => '0274-5559876',
                'address'        => 'Jl. Malioboro No. 12, Yogyakarta',
            ],
            [
                'name'           => 'Toko Jaya Abadi',
                'email'          => 'jaya.abadi@gmail.com',
                'phone'          => '031-5557890',
                'address'        => 'Jl. Raya Darmo No. 78, Surabaya',
            ],
        ];

        foreach ($suppliers as $data) {
            Suppilers::firstOrCreate(['email' => $data['email']], $data);
        }
    }
}
