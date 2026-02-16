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

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset everything first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StockHistory::truncate();
        TransaksiItem::truncate();
        Transaksi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $pasiens = Pasien::all();
        $produks = Produk::all();
        \Illuminate\Support\Facades\Log::info("DummyDataSeeder starting with " . $pasiens->count() . " patients and " . $produks->count() . " products");
        $user = User::first();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : 'Admin';

        if ($pasiens->isEmpty() || $produks->isEmpty())
            return;

        // 2. Generate Events
        $events = [];
        $startDateRange = Carbon::create(2026, 1, 1, 0, 0, 0);
        $limitDate = Carbon::create(2026, 2, 16, 23, 59, 59);
        $secondsInRange = abs($limitDate->diffInSeconds($startDateRange));

        \Illuminate\Support\Facades\Log::info("Seeder Dates: Start=" . $startDateRange->toDateString() . " Limit=" . $limitDate->toDateString() . " Range=" . $secondsInRange);

        // 2a. First, every product starts with a baseline stock on Jan 1st
        foreach ($produks as $p) {
            $events[] = [
                'type' => 'starting_stock',
                'date' => $startDateRange->copy()->addMinutes(rand(0, 59)), // Sometime in the first hour of Jan 1st
                'produk' => $p,
                'qty' => 100,
            ];
            // Reset DB stock to 0 so the seeder can build it up
            $p->update(['stok' => 0]);
        }

        // 2b. Generate exactly 100 Sales Events
        for ($i = 0; $i < 100; $i++) {
            $date = $startDateRange->copy()->addSeconds(rand(3600, $secondsInRange)); // Start after the first hour
            $events[] = [
                'type' => 'sale',
                'date' => $date,
                'pasien' => $pasiens->random(),
            ];
        }

        // 3. Remove Adjustment Events as per user request (only data penjualan)

        // 4. SORT EVENTS BY DATE (Crucial for consistency)
        usort($events, function ($a, $b) {
            return $a['date']->timestamp <=> $b['date']->timestamp;
        });

        // 5. EXECUTE EVENTS CHRONOLOGICALLY
        foreach ($events as $index => $event) {
            $date = $event['date'];

            if ($event['type'] === 'starting_stock') {
                $p = $event['produk'];
                $qty = $event['qty'];

                $p->update(['stok' => $qty]);

                StockHistory::create([
                    'produk_id' => $p->id,
                    'user_id' => $userId,
                    'tipe' => 'Stok Awal',
                    'jumlah' => $qty,
                    'stok_awal' => 0,
                    'stok_akhir' => $qty,
                    'catatan' => 'Saldo Awal Tahun',
                    'created_by' => $userName,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            } elseif ($event['type'] === 'sale') {
                $pasien = $event['pasien'];
                $subtotal = 0;
                $numItems = rand(1, 2);
                $selectedItems = [];

                for ($j = 0; $j < $numItems; $j++) {
                    $prod = $produks->random();
                    $qty = rand(1, 4);
                    $price = $prod->harga_jual;
                    $subtotal += ($price * $qty);
                    $selectedItems[] = ['prod' => $prod, 'qty' => $qty, 'price' => $price];
                }

                $diskon = (rand(0, 10) > 8) ? 50000 : 0;
                $pajak = round(($subtotal - $diskon) * 0.11);
                $grandTotal = $subtotal - $diskon + $pajak;
                $bayar = ceil($grandTotal / 50000) * 50000 ?? $grandTotal;

                $transaksi = Transaksi::create([
                    'no_transaksi' => 'SALE-' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                    'pasien_id' => $pasien->id,
                    'total_harga' => $grandTotal,
                    'status' => 'selesai',
                    'payment_method' => 'Tunai',
                    'subtotal' => $subtotal,
                    'pajak' => $pajak,
                    'bayar' => $bayar,
                    'kembalian' => $bayar - $grandTotal,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                foreach ($selectedItems as $si) {
                    TransaksiItem::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $si['prod']->id,
                        'qty' => $si['qty'],
                        'harga' => $si['price'],
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $prod = $si['prod'];
                    $stok_awal = $prod->stok;
                    $prod->decrement('stok', $si['qty']);
                    $stok_akhir = $prod->stok;

                    StockHistory::create([
                        'produk_id' => $prod->id,
                        'user_id' => $userId,
                        'tipe' => 'Keluar (Penjualan)',
                        'jumlah' => $si['qty'],
                        'stok_awal' => $stok_awal,
                        'stok_akhir' => $stok_akhir,
                        'catatan' => 'Penjualan No: ' . $transaksi->no_transaksi,
                        'created_by' => $userName,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
            } else {
                // Adjustment
                $produk = $produks->random();
                $tipe = $event['tipe_history'];
                $jumlah = rand(1, 10);
                $stok_awal = $produk->stok;

                $isMasuk = (strpos($tipe, 'Masuk') !== false);
                if ($isMasuk) {
                    $produk->increment('stok', $jumlah);
                } else {
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
                    'catatan' => 'Manual Adjustment #' . $index,
                    'created_by' => $userName,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        // Final Touch: Ensure last few transactions are EXACTLY right now for testing
        $latestTrxs = Transaksi::latest()->limit(5)->get();
        foreach ($latestTrxs as $lt) {
            /** @var Transaksi $lt */
            $lt->update(['created_at' => now()]);
            $lt->items()->update(['created_at' => now()]);
            StockHistory::where('catatan', 'like', '%' . $lt->no_transaksi . '%')->update(['created_at' => now()]);
        }
    }
}
