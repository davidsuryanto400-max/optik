<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'no_transaksi',
        'nota_manual',
        'pasien_id',
        'total_harga',
        'status',
        'payment_method',
        'subtotal',
        'diskon',
        'pajak',
        'bayar',
        'bpjs_cover',
        'kembalian'
    ];

    public function items()
    {
        return $this->hasMany(TransaksiItem::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
}
