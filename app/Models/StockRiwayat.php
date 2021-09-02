<?php

namespace App\Models;

class StockRiwayat extends BaseModel
{
    protected $table = 'stock_riwayat';
    protected $primaryKey = 'id_stock_riwayat';
    protected $guarded = [];

    public function stock(){
        return $this->belongsTo(Stock::class,'id_stock','id_stock');
    }
}
