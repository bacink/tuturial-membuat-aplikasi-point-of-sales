<?php

namespace App\Models;

class Kategori extends BaseModel
{

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $guarded = [];

    public function produk()
    {
        return $this->hasMany(Produk::class,'id_kategori','id_kategori');
    }
}
