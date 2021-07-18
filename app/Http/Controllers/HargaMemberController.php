<?php

namespace App\Http\Controllers;

use App\Models\HargaMember;
use App\Models\Kategori;
use App\Models\Member;
use App\Models\Produk;
use App\Models\Setting;
use PDF;
use Illuminate\Http\Request;

class HargaMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::all()->pluck('nama', 'id_member');
        $kategori = Kategori::get();
        $produk = Produk::get();
        // $produk = Produk::with('kategori')->get()->pluck('kategori.nama_kategori');
        return view('member.harga.index', compact('produk','member','kategori'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $harga_beli = customAngka($request->harga_beli);
        $harga_member = customAngka($request->harga_member);
        
        if($harga_member <= $harga_beli){    
            return response()->json('Data gagal disimpan', 400);
        }

        $member = new HargaMember();
        $member->harga_member = customAngka($request->harga_member);
        $member->id_member = $request->id_member;
        $member->id_produk = $request->id_produk;
        
        $member->save();
        return response()->json('Data berhasil disimpan', 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = HargaMember::with('produk')->find($id);
        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $produk = HargaMember::find($id);
    
        $harga_beli = customAngka($request->harga_beli);
        $harga_member = customAngka($request->harga_member);
        
        if($harga_member <= $harga_beli){    
            return response()->json('Data gagal disimpan', 400);
        }
        $produk->id_member = $request->id_member;
        $produk->id_produk = $request->id_produk;
        $produk->harga_member = $harga_member;
            
        $produk->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function data()
    {
        $member = HargaMember::with('member')->with('produk')->orderBy('id_member')->get();
        
        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($member) {
                return '
                    <input type="checkbox" name="id_member[]" value="'. $member->id_member .'">
                ';
            })

            ->addColumn('kode_member', function ($member) {
                return '<span class="label label-success">'. $member->member->kode_member .'<span>';
            })

            ->addColumn('nama', function ($member) {
                return $member->member->nama ;
            })

            ->addColumn('produk', function ($member) {
                return $member->produk->nama_produk ;
            })

            ->addColumn('harga_beli', function ($member) {
                return  format_uang($member->produk->harga_beli);
            })

            ->addColumn('harga_jual', function ($member) {
                return  format_uang($member->produk->harga_jual);
            })
            
            ->addColumn('harga_member', function ($member) {
                return  format_uang($member->harga_member);
            })

            ->addColumn('aksi', function ($member) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('harga-member.update', $member->id_harga_member) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('harga-member.destroy', $member->id_harga_member) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
            ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = HargaMember::find($id);
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }

}
