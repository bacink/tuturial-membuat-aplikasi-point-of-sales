<?php
namespace App\Models\Traits;

use App\Models\StockRiwayat as ModelsStockRiwayat;

trait StockRiwayat{
    
    public function catatRiwayat($idStock,$oldStock,$qty,$symbol,$sumber){

       $stok = new ModelsStockRiwayat();
       $stok->id_stock = $idStock;
       $stok->old_stock = $oldStock;
       $stok->qty = $qty;
       $stok->symbol = $symbol;
       $stok->sumber = $sumber;
       $stok->save();
    }

}