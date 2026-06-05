<?php
// Set 5 random products to have stock 0
$outOfStock = App\Models\Produk::inRandomOrder()->limit(5)->get();
foreach ($outOfStock as $product) {
    $product->stok = 0;
    $product->save();
}

// Set 5 random products to have low stock (stok <= stok_minimum)
$lowStock = App\Models\Produk::whereNotIn('id', $outOfStock->pluck('id'))
    ->inRandomOrder()
    ->limit(5)
    ->get();
foreach ($lowStock as $product) {
    $product->stok = $product->stok_minimum - 1; // ensures it's below minimum or at minimum
    if ($product->stok < 1) {
        $product->stok = 1; // don't make it 0, so it counts as low stock, not out of stock
    }
    $product->save();
}

echo "Stocks updated successfully: 5 out of stock, 5 low stock.";
