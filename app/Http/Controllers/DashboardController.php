<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pasien
        $totalPasien = Pasien::count();

        // Produk terlaris (top 10)
        $produkTerlaris = Produk::select('produks.id', 'produks.nama', DB::raw('SUM(transaksi_items.qty) as total_terjual'))
            ->join('transaksi_items', 'produks.id', '=', 'transaksi_items.produk_id')
            ->groupBy('produks.id', 'produks.nama')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        // Stok rendah (stok <= stok_minimum dan > 0)
        $stokRendah = Produk::where('stok', '>', 0)
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok', 'asc')
            ->get();

        // Stok habis
        $stokHabis = Produk::where('stok', 0)
            ->orderBy('nama', 'asc')
            ->get();

        // Ulang tahun dalam 30 hari ke depan
        $today = Carbon::now();
        $ultah = Pasien::whereNotNull('tgl_lahir')
            ->get()
            ->map(function ($p) use ($today) {
                $nextBday = Carbon::parse($p->tgl_lahir)->year($today->year);
                if ($nextBday->isPast() && !$nextBday->isToday()) {
                    $nextBday->addYear();
                }
                $p->days_until = (int) $today->startOfDay()->diffInDays($nextBday->startOfDay(), false);
                $p->next_bday  = $nextBday;
                return $p;
            })
            ->filter(fn($p) => $p->days_until >= 0 && $p->days_until <= 30)
            ->sortBy('days_until')
            ->values();

        return view('dashboard', compact(
            'totalPasien',
            'produkTerlaris',
            'stokRendah',
            'stokHabis',
            'ultah'
        ));
    }
}
