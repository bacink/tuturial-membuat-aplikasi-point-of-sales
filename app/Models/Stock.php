<?php

namespace App\Models;

class Stock extends BaseModel
{
    protected $table = 'stock';
    protected $primaryKey = 'id_stock';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class,'id_produk','id_produk');
    }

    public function stok_riwayat(){
        return $this->hasMany(StockRiwayat::class,'id_stock','id_stock');
    }
}
