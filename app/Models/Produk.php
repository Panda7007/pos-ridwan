<?php

namespace App\Models;

use App\Models\Stok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $guarded = ["id"];
    protected $with = ["material"];

    public function material()
    {
        return $this->belongsToMany(Stok::class)->withPivot("jumlah");
    }

    public function penjualan_detail()
    {
        return $this->hasMany(PenjualanDetail::class, "produk_id", "id_penjualan_detail");
    }

    public function pembelian_detail()
    {
        return $this->hasMany(PembelianDetail::class, "produk_id", "id_pembelian_detail");
    }
}
