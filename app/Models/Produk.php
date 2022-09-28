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
    protected $with = ["stok"];

    public function stok()
    {
        return $this->belongsToMany(Stok::class)->withPivot("jumlah");
    }
}
