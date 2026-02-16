<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\StockHistory;
use App\Models\Produk;
use App\Models\Pasien;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pasiens = Pasien::all();
        $produks = Produk::all();
        $user = User::first();

        if ($pasiens->isEmpty() || $produks->isEmpty()) {
            return;
        }

        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : 'Admin';

        for ($i = 0; $i < 100; $i++) {
            DB::beginTransaction();
            try {
                // Range: 2026-01-01 to 2026-02-28
                $days = rand(0, 58); // Total days from Jan 1 to Feb 28
                $date = Carbon::create(2026, 1, 1)->addDays($days)->addHours(rand(0, 23))->addMinutes(rand(0, 59));

                $pasien = $pasiens->random();

                // 1. Create Transaction
                $subtotal = 0;
                $numItems = rand(1, 3);
                $selectedItems = [];

                for ($j = 0; $j < $numItems; $j++) {
                    $prod = $produks->random();
                    $qty = rand(1, 2);
                    $price = $prod->harga_jual;
                    $subtotal += ($price * $qty);
                    $selectedItems[] = [
                        'produk' => $prod,
                        'qty' => $qty,
                        'price' => $price
                    ];
                }

                $diskon = (rand(0, 10) > 8) ? 50000 : 0;
                $pajak = round(($subtotal - $diskon) * 0.11);
                $grandTotal = $subtotal - $diskon + $pajak;
                $bayar = ceil($grandTotal / 50000) * 50000;
                $kembalian = $bayar - $grandTotal;

                $transaksi = Transaksi::create([
                    'no_transaksi' => 'SALE-' . str_pad(Transaksi::max('id') + 1, 4, '0', STR_PAD_LEFT),
                    'nota_manual' => null,
                    'pasien_id' => $pasien->id,
                    'total_harga' => $grandTotal,
                    'status' => 'selesai',
                    'payment_method' => ['Tunai', 'Debit', 'Kartu Kredit'][rand(0, 2)],
                    'subtotal' => $subtotal,
                    'diskon' => $diskon,
                    'pajak' => $pajak,
                    'bayar' => $bayar,
                    'bpjs_cover' => 0,
                    'kembalian' => $kembalian,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // 2. Create Items & Stock History
                foreach ($selectedItems as $itemData) {
                    $prod = $itemData['produk'];
                    $qty = $itemData['qty'];

                    TransaksiItem::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $prod->id,
                        'qty' => $qty,
                        'harga' => $itemData['price'],
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $stok_awal = $prod->stok;
                    $prod->decrement('stok', $qty);
                    $stok_akhir = $prod->stok;

                    StockHistory::create([
                        'produk_id' => $prod->id,
                        'user_id' => $userId,
                        'tipe' => 'Keluar (Penjualan)',
                        'jumlah' => $qty,
                        'stok_awal' => $stok_awal,
                        'stok_akhir' => $stok_akhir,
                        'catatan' => 'Auto-generated Sale No: ' . $transaksi->no_transaksi,
                        'created_by' => $userName,
                        'modified_by' => $userName,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Seeder error: " . $e->getMessage());
            }
        }
    }
}
