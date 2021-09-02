<?php

namespace App\Models;

class PembayaranSupplier extends BaseModel
{
    protected $table = 'pembayaran_supplier';

    protected $primaryKey = 'id_pembayaran_supplier';
    protected $guarded = [];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class,'id_pembelian','id_pembelian');
    }
}
