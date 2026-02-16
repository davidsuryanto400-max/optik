<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $fillable = ['nama', 'alamat', 'cabang_id', 'is_active'];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
