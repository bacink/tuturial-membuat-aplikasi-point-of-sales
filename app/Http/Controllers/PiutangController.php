<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiutangController extends Controller
{
    public function index(){
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
            
                return 'Rp. ' .format_uang($penjualan->total_tagihan);

            })
            ->addColumn('total_bayar', function ($penjualan) {
                $pembayaran = $penjualan->pembayaran;
                return 'Rp. '.format_uang($pembayaran->sum('bayar'));

            })
            ->addColumn('piutang', function ($penjualan) {
                $pembayaran = $penjualan->pembayaran;
                $piutang =  $penjualan->total_tagihan - $pembayaran->sum('bayar');

                return 'Rp. ' .format_uang($piutang) ;
            })

            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('piutang.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member'])
            ->make(true);
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
    
}
