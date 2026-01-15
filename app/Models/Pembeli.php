<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Pembeli extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['nama', 'email', 'password', 'telepon', 'alamat'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'pembeli_id');
    }
}
