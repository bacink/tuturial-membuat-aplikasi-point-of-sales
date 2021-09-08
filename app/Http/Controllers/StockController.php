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

        DB::transaction(function () use ($request, $stock, $id) {
            $oldqty = $request->oldqty;
            $stock->qty = $request->qty;
            $stock->update();

            if ($oldqty < $request->qty) {
                $selisih = $request->qty - $oldqty;
                $symbol = '<strong class="text-success">+</strong>' . $selisih;
            } else {
                $selisih = $oldqty - $request->qty;
                $symbol = '-';
                $symbol = '<strong class="text-danger">+</strong>' . $selisih;
            }
            $sumber = 'adjust';
            $this->catatRiwayat($id, $oldqty, $request->qty, $symbol, $sumber);
        });
    }
}
