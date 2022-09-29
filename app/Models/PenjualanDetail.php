<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'id_penjualan_detail';
    protected $guarded = [];
    protected $with = ["produk"];

    public function produk()
    {
        return $this->belongsTo(Produk::class, "produk_id", "id");
    }
}
