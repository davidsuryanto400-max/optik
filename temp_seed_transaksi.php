<?php
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Pasien;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Str;

$pasiens = Pasien::all();
$produks = Produk::where('stok', '>', 0)->get();

if ($pasiens->isEmpty() || $produks->isEmpty()) {
    echo "Pasien or Produk data is missing!";
    exit;
}

$count = 0;
for ($i = 0; $i < 100; $i++) {
    $pasien = $pasiens->random();
    
    // Pick 1 to 3 random products
    $itemCount = rand(1, 3);
    $selectedProduks = $produks->random($itemCount);
    
    $subtotal = 0;
    $items = [];
    
    foreach ($selectedProduks as $produk) {
        $qty = rand(1, 2);
        $harga = $produk->harga_jual;
        $subtotal += $harga * $qty;
        
        $items[] = [
            'produk_id' => $produk->id,
            'qty' => $qty,
            'harga' => $harga
        ];
    }
    
    $diskon = rand(0, 1) == 1 ? rand(10000, 50000) : 0;
    $hasPpn = rand(0, 1) == 1;
    
    $afterDiskon = max(0, $subtotal - $diskon);
    $ppn = $hasPpn ? ($afterDiskon * 0.11) : 0;
    $grandTotal = $afterDiskon + $ppn;
    
    $paymentMethods = ['Cash', 'Debit', 'Transfer', 'BPJS'];
    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
    
    $bpjsCover = 0;
    if ($paymentMethod === 'BPJS') {
        $bpjsClasses = [150000, 300000, 400000];
        $bpjsCover = $bpjsClasses[array_rand($bpjsClasses)];
    }
    
    $totalBayarPasien = max(0, $grandTotal - $bpjsCover);
    
    // Kadang bayar pas, kadang lebih
    $isBayarLebih = rand(0, 1) == 1;
    $bayar = $totalBayarPasien;
    if ($isBayarLebih && $totalBayarPasien > 0) {
        $bayar += rand(1, 5) * 10000;
    }
    $kembalian = $bayar - $totalBayarPasien;

    // Past date randomly within last 6 months
    $createdAt = Carbon::now()->subDays(rand(0, 180));
    
    $noTransaksi = 'TRX-' . $createdAt->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . Str::random(2);
    
    $transaksi = Transaksi::create([
        'no_transaksi' => strtoupper($noTransaksi),
        'nota_manual' => null,
        'pasien_id' => $pasien->id,
        'subtotal' => $subtotal,
        'diskon' => $diskon,
        'pajak' => $ppn,
        'total_harga' => $grandTotal,
        'bpjs_cover' => $bpjsCover,
        'payment_method' => $paymentMethod,
        'bayar' => $bayar,
        'kembalian' => $kembalian,
        'status' => 'selesai',
        'created_at' => $createdAt,
        'updated_at' => $createdAt
    ]);
    
    foreach ($items as $item) {
        TransaksiItem::create([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $item['produk_id'],
            'qty' => $item['qty'],
            'harga' => $item['harga']
        ]);
        
        // Also update stock? Not strictly necessary for dummy visual data, but let's do it to be consistent
        $produk = Produk::find($item['produk_id']);
        if ($produk) {
            $produk->decrement('stok', $item['qty']);
        }
    }
    $count++;
}

echo "$count transaksi berhasil ditambahkan!";
