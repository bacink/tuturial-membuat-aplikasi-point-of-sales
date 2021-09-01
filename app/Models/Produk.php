<?php

namespace App\Models;

class Produk extends BaseModel
{

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function member()
    {
        return $this->belongsTo(HargaMember::class, 'id_produk', 'id_produk');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class,'id_produk','id_produk');
    }
    
    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class,'id_produk','id_produk');
    }

}
