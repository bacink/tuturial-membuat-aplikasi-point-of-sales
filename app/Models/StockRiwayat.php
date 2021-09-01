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

    public function getTanggalInsertAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
        ->format('d, M Y H:i');
    }

    public function getTanggalUbahAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at'])
        ->diffForHumans();
    }
}
