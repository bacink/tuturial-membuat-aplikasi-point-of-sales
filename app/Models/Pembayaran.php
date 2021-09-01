<?php

namespace App\Models;
class Pembayaran extends BaseModel
{
    protected $table = 'pembayaran';

    public function penjualan(){
        return $this->belongsTo(Penjualan::class,'id_penjualan','id_penjualan');
    }
}
