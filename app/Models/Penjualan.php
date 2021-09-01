<?php

namespace App\Models;

class Penjualan extends BaseModel
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $guarded = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id_member', 'id_member');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function detail()
    {
        return $this->hasOne(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }

    public function pembayaran(){
        return $this->hasMany(Pembayaran::class,'id_penjualan','id_penjualan');
    }
}
