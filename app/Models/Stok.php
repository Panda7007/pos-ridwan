<?php

namespace App\Models;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stok extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function produk()
    {
        return $this->belongsToMany(Produk::class)->withPivot("jumlah");
    }
}
