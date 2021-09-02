<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockResource;
use App\Models\Stock;
use App\Models\Traits\StockRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    use StockRiwayat;
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stock = Stock::find($id);
        $stock = new StockResource($stock);
        return response()->json($stock);
    }

    public function update(Request $request, $id)
    {
       
        $stock = Stock::find($id);
       
        DB::transaction(function () use ($request,$stock,$id) {
            $oldqty = $request->oldqty;
            $stock->qty=$request->qty;
            $stock->update();
            $deskripsi = '<span class="label label-warning">+/-</span>';
            $this->catatRiwayat($id,$oldqty,$request->qty,$deskripsi);
        });
    }
}
