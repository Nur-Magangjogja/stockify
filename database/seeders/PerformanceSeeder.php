<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use App\Models\User;
use Carbon\Carbon;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Mulai membuat ribuan data untuk test performa...');

        $productIds = DB::table('products')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($productIds) || empty($userIds)) {
            $this->command->error('Pastikan tabel products dan users sudah memiliki data!');
            return;
        }

        $totalData = 10000; // 10 ribu data
        $chunkSize = 1000;
        
        $types = ['Masuk', 'Keluar'];
        
        $startDate = Carbon::now()->subMonths(6); // Data 6 bulan ke belakang
        
        // Buat dan urutkan tanggal agar transaksi kronologis
        $dates = [];
        for ($i = 0; $i < $totalData; $i++) {
            $dates[] = (clone $startDate)->addMinutes(rand(0, 6 * 30 * 24 * 60)); // Random menit dalam 6 bulan
        }
        sort($dates);

        // Tracking stok saat ini agar tidak minus
        $currentStock = array_fill_keys($productIds, 0);

        $this->command->getOutput()->progressStart($totalData);

        for ($i = 0; $i < $totalData; $i += $chunkSize) {
            $data = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                if (($i + $j) >= $totalData) break;
                
                $currentDate = $dates[$i + $j];
                $productId = $productIds[array_rand($productIds)];
                
                $type = $types[array_rand($types)];
                $qty = rand(1, 100);
                
                // Mencegah stok menjadi negatif
                if ($type === 'Keluar') {
                    if ($currentStock[$productId] < $qty) {
                        // Jika mau keluar tapi stok kurang, paksa jadi barang masuk
                        $type = 'Masuk';
                    }
                }
                
                // Update tracker
                if ($type === 'Masuk') {
                    $currentStock[$productId] += $qty;
                } else {
                    $currentStock[$productId] -= $qty;
                }
                
                $data[] = [
                    'product_id' => $productId,
                    'user_id' => $userIds[array_rand($userIds)],
                    'type' => $type,
                    'quantity' => $qty,
                    'date' => $currentDate->format('Y-m-d'),
                    'status' => 'Diterima', // Kebanyakan diterima untuk test grafik
                    'notes' => 'Data dummy performa',
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate,
                ];
            }
            
            DB::table('stock_transactions')->insert($data);
            $this->command->getOutput()->progressAdvance(count($data));
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Selesai! Berhasil menambahkan ' . $totalData . ' data transaksi.');
    }
}
