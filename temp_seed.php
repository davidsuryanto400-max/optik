<?php
$kategoris = [
    ['nama' => 'Plastik', 'tipe_id' => 9],
    ['nama' => 'Besi', 'tipe_id' => 9],
    ['nama' => 'Titanium', 'tipe_id' => 9],
    ['nama' => 'Single Vision', 'tipe_id' => 10],
    ['nama' => 'Bifocal', 'tipe_id' => 10],
    ['nama' => 'Progressive', 'tipe_id' => 10],
    ['nama' => 'Photochromic', 'tipe_id' => 10],
    ['nama' => 'Blue Ray', 'tipe_id' => 10],
    ['nama' => 'Clear', 'tipe_id' => 11],
    ['nama' => 'Warna', 'tipe_id' => 11],
    ['nama' => 'Toric', 'tipe_id' => 11]
];

foreach ($kategoris as $k) {
    App\Models\Kategori::firstOrCreate($k);
}
echo "Seeded successfully!";
