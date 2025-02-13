<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{

    use HasFactory;

    protected $guarded = [];

    protected $table = 'penjualan';

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }


    public function faktur()
    {
        return $this->belongsTo(FakturModel::class, 'faktur_id', 'id');
    }
}
