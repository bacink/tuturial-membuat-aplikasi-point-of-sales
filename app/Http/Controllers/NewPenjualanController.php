<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewPenjualanController extends Controller
{
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            // Step 1 : Search data
            $data = PenjualanDetail::whereIdPenjualan($request->id_penjualan)->get();
            $total_item = $data->sum('jumlah');
            $data = Penjualan::find($request->id_penjualan);
            $data->total_tagihan = customAngka($request->total_tagihan);
            $data->total_item = $total_item;

            $data->save();

            // Step 2 : Update data
            $pembayaran = new Pembayaran();
            $pembayaran->id_penjualan = $request->id_penjualan;
            $pembayaran->piutang = customAngka($request->total_tagihan);
            $pembayaran->bayar = customAngka($request->total_bayar);
            $pembayaran->sisa = customAngka($request->sisa_bayar);
            $pembayaran->save();

            DB::commit();
            return redirect()->route('new.transaksi.transaksi.index')->with($request->session()->flash('status', 'Pembayaran berhasil disimpan!'));
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e, 500);
        }
    }
}
