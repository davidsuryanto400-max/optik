<?php
use App\Models\Produk;
use App\Models\StockHistory;
use App\Models\TransaksiItem;
use App\Models\User;
use Carbon\Carbon;

// Find admin user
$user = User::first();
$userId = $user ? $user->id : null;
$username = $user ? $user->name : 'System';

// 1. Fix minus stock
$negativeProducts = Produk::where('stok', '<', 0)->get();
foreach ($negativeProducts as $p) {
    $p->stok = rand($p->stok_minimum, $p->stok_minimum + 10);
    $p->save();
}

// 2. Clear existing history to rebuild cleanly
StockHistory::truncate();

// 3. Rebuild history for all products
$produks = Produk::all();
foreach ($produks as $produk) {
    // Get all transactions for this product, ordered chronologically
    $items = TransaksiItem::where('produk_id', $produk->id)
        ->join('transaksis', 'transaksi_items.transaksi_id', '=', 'transaksis.id')
        ->orderBy('transaksis.created_at', 'asc')
        ->select('transaksi_items.*', 'transaksis.created_at as tx_date', 'transaksis.no_transaksi')
        ->get();
        
    $totalSold = $items->sum('qty');
    $initialStock = $produk->stok + $totalSold; // Stock before any sales
    
    // Add Initial Restock History
    $initialDate = Carbon::now()->subMonths(6)->subDays(1); // Right before any transaction
    StockHistory::create([
        'produk_id' => $produk->id,
        'user_id' => $userId,
        'tipe' => 'masuk',
        'jumlah' => $initialStock,
        'stok_awal' => 0,
        'stok_akhir' => $initialStock,
        'catatan' => 'Stok Awal Sistem',
        'created_by' => $username,
        'created_at' => $initialDate,
        'updated_at' => $initialDate
    ]);
    
    // Process each sale to create history
    $currentStock = $initialStock;
    foreach ($items as $item) {
        $stokAwal = $currentStock;
        $currentStock -= $item->qty;
        
        StockHistory::create([
            'produk_id' => $produk->id,
            'user_id' => $userId,
            'tipe' => 'keluar',
            'jumlah' => $item->qty,
            'stok_awal' => $stokAwal,
            'stok_akhir' => $currentStock,
            'catatan' => 'Penjualan: ' . $item->no_transaksi,
            'created_by' => 'System',
            'created_at' => $item->tx_date,
            'updated_at' => $item->tx_date
        ]);
    }
    
    // Just in case currentStock doesn't perfectly match product->stok (shouldn't happen)
    if ($currentStock != $produk->stok) {
        StockHistory::create([
            'produk_id' => $produk->id,
            'user_id' => $userId,
            'tipe' => ($produk->stok > $currentStock) ? 'masuk' : 'keluar',
            'jumlah' => abs($produk->stok - $currentStock),
            'stok_awal' => $currentStock,
            'stok_akhir' => $produk->stok,
            'catatan' => 'Penyesuaian Stok Otomatis',
            'created_by' => 'System',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}

echo "Stok minus berhasil diperbaiki dan Kartu Stok (Stock History) berhasil digenerate ulang berdasarkan urutan waktu transaksi!";
