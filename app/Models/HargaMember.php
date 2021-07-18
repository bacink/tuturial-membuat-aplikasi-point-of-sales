<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaMember extends Model
{
    protected $table = 'harga_member';
    protected $primaryKey = 'id_harga_member';
    protected $guarded = [];

    public function member(){
        return $this->belongsTo(Member::class,'id_member','id_member');
    }

    public function produk(){
        return $this->belongsTo(Produk::class,'id_produk','id_produk');
    }
}
