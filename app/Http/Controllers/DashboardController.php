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
        $totalPasien = Pasien::count();
        $totalPenjualan = Transaksi::sum('total_harga');

        $avgUmur = Pasien::selectRaw('AVG(TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())) as avg_age')->value('avg_age');
        $avgUmur = (int) round($avgUmur);

        $produkTerlaris = Produk::select('produks.nama', DB::raw('SUM(transaksi_items.qty) as total_terjual'))
            ->join('transaksi_items', 'produks.id', '=', 'transaksi_items.produk_id')
            ->groupBy('produks.id', 'produks.nama')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $stokRendah = Produk::where('stok', '<=', 10)->where('stok', '>', 0)->orderBy('stok', 'asc')->limit(10)->get();
        $stokHabis = Produk::where('stok', 0)->limit(10)->get();

        // Upcoming birthdays (next 30 days) with days remaining calculation
        $today = Carbon::now();
        $ultah = Pasien::all()->map(function ($p) use ($today) {
            $nextBday = Carbon::parse($p->tgl_lahir)->year($today->year);
            if ($nextBday->isPast()) {
                $nextBday->addYear();
            }
            $p->days_until = (int) $today->diffInDays($nextBday, false);
            $p->next_bday = $nextBday;
            return $p;
        })
            ->filter(function ($p) {
                return $p->days_until >= 0 && $p->days_until <= 30;
            })
            ->sortBy('days_until')
            ->take(10);

        return view('dashboard', compact('totalPasien', 'totalPenjualan', 'avgUmur', 'produkTerlaris', 'stokRendah', 'stokHabis', 'ultah'));
    }
}
