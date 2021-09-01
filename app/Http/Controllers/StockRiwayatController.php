<?php

namespace App\Http\Controllers;

use App\Models\StockRiwayat;
use Illuminate\Http\Request;

class StockRiwayatController extends Controller
{
    public function detailData($id_stock)
    {
        $data = StockRiwayat::whereIdStock($id_stock)->with('stock')->get();
        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($data) {
                return '<span class="label label-success">' . $data->stock->produk->kode_produk . '</span>';
            })
            ->addColumn('produk', function ($data) {
               return $data->stock->produk->nama_produk;
            })
            ->addColumn('kategori', function ($data) {
                return $data->stock->produk->kategori->nama_kategori;
            })
            ->addColumn('merk', function ($data) {
            return $data->stock->produk->merk;
            })
            ->addColumn('qty', function ($data) {
                return $data->qty;
            })
            ->addColumn('deskripsi', function ($data) {
                return $data->deskripsi;
            })
            ->addColumn('tanggal', function ($data) {
                
                $tanggal = '<span class="label label-success">'.\Carbon\Carbon::parse($data->created_at)
                ->format('d, M Y H:i').'</span>';

                $waktu ='<span class="label label-success"><i class="fa fa-clock-o"></i> '.\Carbon\Carbon::parse($data->updated_at)
                ->diffForHumans().'</span>';
                return $tanggal .' : '. $waktu;
                })
                
            ->rawColumns(['kode_produk','tanggal'])
            ->make(true);
    }
}
