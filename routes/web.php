<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ReportController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('cabang', CabangController::class);
Route::resource('gudang', GudangController::class);
Route::resource('tipe', TipeController::class);
Route::resource('kategori', KategoriController::class);
Route::post('produk/check-password', [ProdukController::class, 'checkPassword'])->name('produk.checkPassword');
Route::post('produk/{produk}/update-stock', [ProdukController::class, 'updateStock'])->name('produk.updateStock');
Route::resource('produk', ProdukController::class);
Route::get('/pasien/{id}/print', [PasienController::class, 'print'])->name('pasien.print');
Route::resource('pasien', PasienController::class);
Route::get('transaksi/get-products', [TransaksiController::class, 'getProducts']);
Route::get('transaksi/get-pasiens', [TransaksiController::class, 'getPasiens']);
Route::post('transaksi/check-password', [TransaksiController::class, 'checkPassword']);
Route::get('transaksi/debug/{id}', function ($id) {
    return App\Models\Transaksi::with('items.produk')->find($id);
});
Route::get('/transaksi/{id}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
Route::resource('transaksi', TransaksiController::class);

Route::get('/report/rekap-stok', [ReportController::class, 'rekapStok'])->name('report.rekapStok');
Route::get('/report/kartu-stok', [ReportController::class, 'kartuStok'])->name('report.kartuStok');
Route::get('/report/penjualan', [ReportController::class, 'penjualan'])->name('report.penjualan');
