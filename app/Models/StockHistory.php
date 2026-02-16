<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'produk_id',
        'user_id',
        'tipe',
        'jumlah',
        'stok_awal',
        'stok_akhir',
        'catatan',
        'created_by',
        'modified_by'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
