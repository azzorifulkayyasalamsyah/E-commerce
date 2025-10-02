<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $fillable = ['nama', 'email', 'alamat', 'telepon'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'pembeli_id'); // 1 pembeli bisa beli banyak produk
    }
}
