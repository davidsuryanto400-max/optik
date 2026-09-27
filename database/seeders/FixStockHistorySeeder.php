<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\StockHistory;
use App\Models\User;

/**
 * FixStockHistorySeeder
 *
 * Dijalankan untuk memperbaiki data: setiap produk yang memiliki stok > 0
 * tetapi tidak memiliki record stock history akan dibuatkan record
 * "Penyesuaian Awal" agar riwayat stok konsisten dengan nilai stok saat ini.
 *
 * Jalankan dengan: php artisan db:seed --class=FixStockHistorySeeder
 */
class FixStockHistorySeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('role', 'administrator')
            ->orWhere('role', 'superadministrator')
            ->first();
        $adminId   = $adminUser ? $adminUser->id : null;
        $adminName = $adminUser ? $adminUser->name : 'Admin';

        $produks = Produk::all();
        $fixed   = 0;

        foreach ($produks as $produk) {
            $hasHistory = StockHistory::where('produk_id', $produk->id)->exists();

            if (! $hasHistory && $produk->stok >= 0) {
                // Buat record penyesuaian agar stok_akhir = stok saat ini
                StockHistory::create([
                    'produk_id'   => $produk->id,
                    'user_id'     => $adminId,
                    'tipe'        => 'Stok Awal',
                    'jumlah'      => $produk->stok,
                    'stok_awal'   => 0,
                    'stok_akhir'  => $produk->stok,
                    'catatan'     => 'Penyesuaian awal (otomatis)',
                    'created_by'  => $adminName,
                    'modified_by' => $adminName,
                    'created_at'  => $produk->created_at ?? now(),
                    'updated_at'  => $produk->created_at ?? now(),
                ]);
                $fixed++;
            }
        }

        $this->command->info("Selesai: {$fixed} produk dibuatkan record stok awal.");
    }
}
