<?php
use App\Models\RiwayatPemeriksaan;
use App\Models\Pasien;

$riwayats = RiwayatPemeriksaan::all();

function generateRandomSph() {
    $vals = [];
    for ($i = -5.00; $i <= 3.00; $i += 0.25) {
        $vals[] = number_format($i, 2, '.', '');
    }
    return $vals[array_rand($vals)];
}

function generateRandomCyl() {
    $vals = [];
    for ($i = -3.00; $i <= 0.00; $i += 0.25) {
        $vals[] = number_format($i, 2, '.', '');
    }
    return $vals[array_rand($vals)];
}

$count = 0;
foreach ($riwayats as $riwayat) {
    // Generate different random values for each history entry
    $sph_r = generateRandomSph();
    $cyl_r = generateRandomCyl();
    
    // Left eye usually similar to right eye but can vary slightly
    $sph_l = (rand(0, 10) > 7) ? generateRandomSph() : $sph_r;
    $cyl_l = (rand(0, 10) > 7) ? generateRandomCyl() : $cyl_r;
    
    $pd = rand(58, 72);
    
    $riwayat->sph_r = ($sph_r > 0 ? '+' : '') . $sph_r;
    $riwayat->cyl_r = ($cyl_r > 0 ? '+' : '') . $cyl_r;
    $riwayat->sph_l = ($sph_l > 0 ? '+' : '') . $sph_l;
    $riwayat->cyl_l = ($cyl_l > 0 ? '+' : '') . $cyl_l;
    $riwayat->pd = $pd;
    
    $riwayat->save();
    
    // Also, if this is the most recent history for the patient, update the patient's main profile
    // We'll just update the patient's profile to match their latest history record randomly.
    $pasien = $riwayat->pasien;
    if ($pasien) {
        $latest = $pasien->riwayatPemeriksaans()->latest()->first();
        if ($latest && $latest->id == $riwayat->id) {
            $pasien->sph_r = $riwayat->sph_r;
            $pasien->cyl_r = $riwayat->cyl_r;
            $pasien->sph_l = $riwayat->sph_l;
            $pasien->cyl_l = $riwayat->cyl_l;
            $pasien->pd = $riwayat->pd;
            $pasien->save();
        }
    }
    
    $count++;
}

echo "$count data riwayat pemeriksaan dan profil utama pasien berhasil diacak agar lebih bervariasi!";
