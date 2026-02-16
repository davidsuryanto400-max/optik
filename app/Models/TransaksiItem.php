<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    protected $fillable = ['transaksi_id', 'produk_id', 'qty', 'harga'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
