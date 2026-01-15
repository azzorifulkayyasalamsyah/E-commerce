<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode', 'deskripsi', 'harga', 'stok', 'pembeli_id'];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }
}
