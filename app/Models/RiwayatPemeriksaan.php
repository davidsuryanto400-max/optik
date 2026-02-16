<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPemeriksaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'transaksi_id',
        'sph_r',
        'cyl_r',
        'sph_l',
        'cyl_l',
        'pd',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
