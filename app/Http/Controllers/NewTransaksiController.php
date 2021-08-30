<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NewTransaksiController extends Controller
{
    public function index()
    {
        $member = Member::orderBy('nama')->get();
        $produk = Produk::orderBy('nama_produk')->get();
        return view('new.transaksi.index', compact('member'));
    }

    public function show($id)
    {
        $data = PenjualanDetail::whereIdPenjualan($id)->get();
        $data = CartResource::collection($data);
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function member(Request $request, $id)
    {
        $data = new Penjualan();
        $data->id_member = $id;
        $data->total_item = 0;
        $data->total_tagihan = 0;
        $data->id_user = auth()->id();

        $data->save();
        return redirect()->route('new.transaksi.detail', $id);
    }

    public function detail($id)
    {
        $produk = Produk::get();
        $penjualan = Penjualan::latest()->first();
        $member = Member::find($id);
        return view('new.transaksi.detail', compact('member', 'produk', 'penjualan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required',
            'id_produk' => 'required',
            'harga_jual' => 'required',
            'jumlah' => 'required',
            'subtotal' => 'required',
        ]);

        $data = new PenjualanDetail();
        $data->id_penjualan = $request->id_penjualan;

        $data->id_produk = $request->id_produk;
        $data->harga_jual = customAngka($request->harga_jual);
        $data->jumlah = $request->jumlah;
        $data->subtotal = customAngka($request->subtotal);
        $data->save();
        return redirect()->route('new.transaksi.detail', $request->id_member)->with(
            $request->session()->flash('status', 'Produk berhasil di masukan ke keranjang!')
        );
    }

    public function delete(Request $request, $transaksi)
    {
        $data  = PenjualanDetail::find($transaksi);
        $penjualan = Penjualan::find($data->id_penjualan);
        $data->delete();
        return redirect()->route('new.transaksi.detail', $penjualan->id_member)->with(
            $request->session()->flash('status', 'Produk berhasil di hapus dari keranjang!')
        );
    }
}
