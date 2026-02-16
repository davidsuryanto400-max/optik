<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Tipe;
use App\Models\Gudang;

class ReportController extends Controller
{
    public function rekapStok(Request $request)
    {
        $query = Produk::with(['tipe', 'kategori', 'gudang']);

        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('tipe_id') && $request->tipe_id) {
            $query->where('tipe_id', $request->tipe_id);
        }

        if ($request->has('gudang_id') && $request->gudang_id) {
            $query->where('gudang_id', $request->gudang_id);
        }

        $produks = $query->get();
        $all_kategoris = Kategori::all();
        $all_tipes = Tipe::all();
        $all_gudangs = Gudang::where('is_active', true)->get();

        return view('reports.rekap_stok', compact('produks', 'all_kategoris', 'all_tipes', 'all_gudangs'));
    }

    public function kartuStok(Request $request)
    {
        $all_produks = Produk::orderBy('nama')->get();
        $produk = null;
        $history = collect();
        $initialBalance = 0; // Initialize with a default value
        $startDate = null; // Initialize with a default value
        $visibleStock = 0; // Initialize with a default value

        $endDate = $request->end_date ?? now()->toDateString();

        if ($request->has('produk_id') && $request->produk_id) {
            $produk = Produk::with(['tipe', 'kategori', 'gudang'])->findOrFail($request->produk_id);

            // Get all history up to end_date with precise timestamp comparison
            $history = \App\Models\StockHistory::where('produk_id', $produk->id)
                ->where('created_at', '<=', $endDate . ' 23:59:59')
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            // The balance at the end of the results (Visible Stock)
            $lastRecord = $history->last();
            $visibleStock = $lastRecord ? $lastRecord->stok_akhir : $produk->stok;

            // Find the very first history record to determine the starting balance
            $firstRecord = \App\Models\StockHistory::where('produk_id', $produk->id)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->first();

            $initialBalance = $firstRecord ? $firstRecord->stok_awal : $produk->stok;
            $startDate = $firstRecord ? $firstRecord->created_at->toDateString() : '2026-01-01';
        }

        return view('reports.kartu_stok', compact('all_produks', 'produk', 'history', 'endDate', 'initialBalance', 'startDate', 'visibleStock'));
    }

    public function penjualan(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $query = \App\Models\Transaksi::with(['pasien', 'items.produk'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', 'selesai');

        $transaksis = $query->latest()->get();

        // Calculate summaries
        $summary = [
            'total_sales' => $transaksis->sum('total_harga'),
            'total_count' => $transaksis->count(),
            'payment_methods' => $transaksis->groupBy('payment_method')->map->count(),
        ];

        return view('reports.penjualan', compact('transaksis', 'startDate', 'endDate', 'summary'));
    }
}
