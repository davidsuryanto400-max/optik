<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;

class FixBpjsTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = Transaksi::where('payment_method', 'BPJS')->get();

        foreach ($transactions as $t) {
            // Assume the shortage is the BPJS cover
            $shortage = $t->total_harga - $t->bayar;

            if ($shortage > 0) {
                $t->bpjs_cover = $shortage;
                $t->save();
                $this->command->info("Updated Transaction {$t->no_transaksi}: Set BPJS Cover to {$shortage}");
            }
        }
    }
}
