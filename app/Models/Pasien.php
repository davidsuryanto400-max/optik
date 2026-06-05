<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'tgl_lahir',
        'last_exam_date',
        'sph_r',
        'cyl_r',
        'ax_r',
        'add_r',
        'sph_l',
        'cyl_l',
        'ax_l',
        'add_l',
        'pd'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function riwayatPemeriksaans()
    {
        return $this->hasMany(RiwayatPemeriksaan::class)->latest();
    }

    public function getUsiaAttribute()
    {
        return $this->tgl_lahir ? $this->tgl_lahir->age . ' tahun' : '-';
    }

    public function getPeriksaTerakhirAttribute()
    {
        if ($this->last_exam_date) {
            return \Carbon\Carbon::parse($this->last_exam_date)->format('d/m/Y');
        }
        $last_transaksi = $this->transaksis()->latest()->first();
        return $last_transaksi ? $last_transaksi->created_at->format('d/m/Y') : '-';
    }
}
