<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockHistory;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;

class StockHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = Produk::all();
        if ($produks->isEmpty()) {
            return;
        }

        $user = User::first();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : 'Admin';

        $tipes = [
            'Stok Awal',
            'Masuk',
            'Keluar (Manual)',
            'Keluar (Penjualan)',
            'Masuk (Edit)',
            'Keluar (Edit)',
            'Masuk (Void)'
        ];

        for ($i = 0; $i < 100; $i++) {
            $produk = $produks->random();
            $tipe = $tipes[array_rand($tipes)];
            $jumlah = rand(1, 10);

            // Range: 2026-01-01 to 2026-02-28
            $days = rand(0, 58);
            $date = Carbon::create(2026, 1, 1)->addDays($days)->addHours(rand(0, 23))->addMinutes(rand(0, 59));

            $stok_awal = $produk->stok;
            $isMasuk = (strpos($tipe, 'Masuk') !== false || $tipe === 'Stok Awal');

            if ($isMasuk) {
                $produk->increment('stok', $jumlah);
            } else {
                // For 'Keluar', ensure we don't go below 0 during seeding if possible, but keep it realistic
                $produk->decrement('stok', $jumlah);
            }
            $stok_akhir = $produk->stok;

            StockHistory::create([
                'produk_id' => $produk->id,
                'user_id' => $userId,
                'tipe' => $tipe,
                'jumlah' => $jumlah,
                'stok_awal' => $stok_awal,
                'stok_akhir' => $stok_akhir,
                'catatan' => 'Sample record #' . ($i + 1),
                'created_by' => $userName,
                'modified_by' => $userName,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
