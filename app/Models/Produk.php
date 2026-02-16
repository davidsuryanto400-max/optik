<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockHistory;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'nama',
        'merek',
        'tipe_id',
        'kategori_id',
        'warna',
        'gudang_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum'
    ];

    public function tipe()
    {
        return $this->belongsTo(Tipe::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class)->latest();
    }
}
