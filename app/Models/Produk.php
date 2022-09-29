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
}
