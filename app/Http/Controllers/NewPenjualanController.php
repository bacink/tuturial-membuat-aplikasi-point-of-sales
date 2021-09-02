<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Stock;
use App\Models\Traits\StockRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewPenjualanController extends Controller
{
    use StockRiwayat;

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            // Step 1 : Search data
            $penjualanDetail = PenjualanDetail::whereIdPenjualan($request->id_penjualan)->get();
            session(['id_penjualan' => $request->id_penjualan]);

            // Step 2 : Update stock
            
            $total_item = $penjualanDetail->sum('jumlah');
            $data = Penjualan::find($request->id_penjualan);
            $data->total_tagihan = customAngka($request->total_tagihan);
            $data->total_item = $total_item;

            $data->save();

            // Step 3 : Update data
            $pembayaran = new Pembayaran();
            $pembayaran->id_penjualan = $request->id_penjualan;
            $pembayaran->piutang = customAngka($request->total_tagihan);
            $pembayaran->bayar = customAngka($request->total_bayar);
            $pembayaran->sisa = customAngka($request->sisa_bayar);
            $pembayaran->save();

            DB::commit();
            $this->updateStock($request,$penjualanDetail);
            // return redirect('new/transaksi');
            return redirect()->route('transaksi.selesai');

            // return redirect()->route('new.transaksi.transaksi.index')->with($request->session()->flash('status', 'Pembayaran berhasil disimpan!'));
        
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 500);
        }
    }

    public function updateStock(Request $request,$data)
    {
        DB::transaction(function () use ($data,$request){
            
            foreach($data as $row){
                $stock = Stock::whereIdProduk($row->id_produk)->first();
                $newQtyStock = ($stock->qty) - ($row->jumlah);
                
                $deskripsi = '<span class="label label-danger">-</span>';
                
                $this->catatRiwayat($stock->id_stock,$stock->qty,$row->jumlah,$deskripsi);
                $stock->qty = $newQtyStock;
                $stock->update();
            }    
        
            
        });
        
    }
}
