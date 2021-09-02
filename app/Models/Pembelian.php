<?php

namespace App\Models;

class Pembelian extends BaseModel
{
    protected $table = 'pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function pembayaranSupplier()
    {
        return $this->hasMany(PembayaranSupplier::class, 'id_pembelian', 'id_pembelian');
    }
}
