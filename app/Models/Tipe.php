<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    protected $fillable = ['nama'];

    public function kategoris()
    {
        return $this->hasMany(Kategori::class);
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}
