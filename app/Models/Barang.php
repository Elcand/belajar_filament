<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['nama_barang', 'kode_barang', 'harga_barang'];

    public function detail()
    {
        return $this->hasMany(DetailFakturModel::class);
    }
    public function penjualan()
    {
        return $this->hasMany(PenjualanModel::class, 'barang_id', 'id');
    }
}
