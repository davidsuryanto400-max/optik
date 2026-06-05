<?php
$gudang = App\Models\Gudang::first();
if (!$gudang) {
    $cabang = App\Models\Cabang::first();
    if (!$cabang) {
        $cabang = App\Models\Cabang::create(['nama' => 'Pusat', 'alamat' => 'Jakarta']);
    }
    // create Gudang without tipe since it doesn't exist in the initial commit
    $gudang = App\Models\Gudang::create(['nama' => 'Gudang Utama', 'cabang_id' => $cabang->id, 'alamat' => '-', 'is_active' => true]);
}
$tipes = App\Models\Tipe::all();
if ($tipes->isEmpty()) {
    echo 'No Tipe found';
    exit;
}
$count = 0;
for ($i=0; $i<50; $i++) {
    $tipe = $tipes->random();
    $kategori = App\Models\Kategori::where('tipe_id', $tipe->id)->inRandomOrder()->first();
    App\Models\Produk::factory()->create([
        'gudang_id' => $gudang->id,
        'tipe_id' => $tipe->id,
        'kategori_id' => $kategori ? $kategori->id : null
    ]);
    $count++;
}
echo "$count products generated successfully!";
