<?php
namespace App\Models\Traits;

use App\Models\StockRiwayat as ModelsStockRiwayat;

trait StockRiwayat{
    
    public function catatRiwayat($idStock,$qty,$deskripsi){

       $stok = new ModelsStockRiwayat();
       $stok->id_stock = $idStock;
       $stok->qty = $qty;
       $stok->deskripsi = $deskripsi;
       $stok->save();

    }

}