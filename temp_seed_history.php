<?php
use App\Models\Transaksi;
use App\Models\RiwayatPemeriksaan;
use App\Models\Pasien;

// Get the latest 100 transactions (the ones we just created)
$transaksis = Transaksi::latest()->take(100)->get();

$count = 0;
foreach ($transaksis as $transaksi) {
    $pasien = $transaksi->pasien;
    
    if ($pasien) {
        // Create RiwayatPemeriksaan using the patient's current prescription or generate slight variations
        $sph_r = $pasien->sph_r ?? '0.00';
        $cyl_r = $pasien->cyl_r ?? '0.00';
        $sph_l = $pasien->sph_l ?? '0.00';
        $cyl_l = $pasien->cyl_l ?? '0.00';
        $pd = $pasien->pd ?? 62;
        
        RiwayatPemeriksaan::create([
            'pasien_id' => $pasien->id,
            'transaksi_id' => $transaksi->id,
            'sph_r' => $sph_r,
            'cyl_r' => $cyl_r,
            'sph_l' => $sph_l,
            'cyl_l' => $cyl_l,
            'pd' => $pd,
            'created_at' => $transaksi->created_at,
            'updated_at' => $transaksi->updated_at,
        ]);
        
        // Also update the patient's last_exam_date if the transaction is newer
        if (!$pasien->last_exam_date || $transaksi->created_at > $pasien->last_exam_date) {
            $pasien->last_exam_date = $transaksi->created_at->format('Y-m-d');
            $pasien->save();
        }
        
        $count++;
    }
}

echo "$count riwayat pemeriksaan berhasil ditambahkan dan dihubungkan dengan transaksi!";
