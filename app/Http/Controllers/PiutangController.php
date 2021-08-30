<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiutangController extends Controller
{
    public function index()
    {
        return view('piutang.index');
    }

    public function data()
    {
        $id = Auth::id();
        $penjualan = Penjualan::with('member')
            ->where('id_user', $id)
            ->orderBy('id_penjualan', 'desc')->get();


        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('total_tagihan', function ($penjualan) {

                return 'Rp. ' . format_uang($penjualan->total_tagihan);
            })
            ->addColumn('total_bayar', function ($penjualan) {
                $pembayaran = $penjualan->pembayaran;
                return 'Rp. ' . format_uang($pembayaran->sum('bayar'));
            })
            ->addColumn('piutang', function ($penjualan) {
                $pembayaran = $penjualan->pembayaran;
                $piutang =  $penjualan->total_tagihan - $pembayaran->sum('bayar');

                return 'Rp. ' . format_uang($piutang);
            })

            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('piutang.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="showBayar(`' . route('piutang.bayar.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-cash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showBayar($id)
    {
        $data = Pembayaran::whereIdPenjualan($id)->orderBy('id_pembayaran','desc')->first();

        return response()->json($data);
    }

    public function bayar(Request $request, $id)
    {
        $pembayaran = new Pembayaran();

        $piutang = customAngka($request->piutang);
        $bayar = customAngka($request->bayar);
        $sisa = customAngka($request->sisa);
        $pembayaran->id_penjualan = $id;
        $pembayaran->piutang = $sisa;
        $pembayaran->bayar = $bayar;
        $pembayaran->sisa = $sisa - $bayar;
        $pembayaran->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $detail = Pembayaran::where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('tanggal_bayar', function ($detail) {
                return tanggal_indonesia($detail->created_at, false);
            })
            ->addColumn('piutang', function ($detail) {
                return 'Rp. ' . format_uang($detail->piutang);
            })
            ->addColumn('bayar', function ($detail) {
                return 'Rp. ' . format_uang($detail->bayar);
            })
            ->addColumn('sisa', function ($detail) {
                return 'Rp. ' . format_uang($detail->sisa);
            })
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $produk = Pembayaran::find($id);

        $piutang = customAngka($request->piutang);
        $bayar = customAngka($request->bayar);
        $sisa = customAngka($request->sisa);

        $request['piutang'] = $sisa;
        $request['bayar'] = $bayar;
        $request['sisa'] = $sisa-$bayar;

        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

}
