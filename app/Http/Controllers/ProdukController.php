<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdukResource;
use App\Models\HargaMember;
use App\Models\Kategori;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PDF;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
        return view('produk.index', compact('kategori'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function member($produk_id, $member_id)
    {
        try {
            $data = HargaMember::whereIdMember($member_id)->whereIdProduk($produk_id)->with('produk')->firstOrFail();
        } catch (ModelNotFoundException $e) {

            $data = Produk::findOrFail($produk_id);
        }
        return $data;
    }

    public function data()
    {

        $produk = Produk::with('stock','kategori')->get();
        
        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_produk[]" value="' . $produk->id_produk . '">
                ';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">' . $produk->kode_produk . '</span>';
            })
            ->addColumn('kategori', function ($produk) {
                if ($produk->kategori) {
                    return $produk->kategori->nama_kategori;
                 }else{
                     return 0;
                 }
            })
            ->addColumn('harga_beli', function ($produk) {
                $id_produk = $produk->id_produk;
                $harga = PembelianDetail::whereIdProduk($id_produk)->latest()->first();
                if($harga){
                    return format_uang($harga->harga_beli);
                }
                else{
                    return format_uang(0);
                }
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                if ($produk->stock) {
                   return $produk->stock->qty;
                }else{
                    return 0;
                }
            })
            ->addColumn('aksi', function ($produk) {
                if($produk->stock == null){
                    $id_stock = 0;
                    $btn_riwayat ='';
                    $btn_stock='<a href="' . route('pembelian.index') . '" class="btn btn-xs btn-success btn-flat">Tambah Stock</a>';
                }else{
                    $id_stock = $produk->stock->id_stock;
                    $btn_riwayat = '<button type="button" onclick="showDetail(`' . route('stock.riwayat.detail.data', $id_stock) . '`)" class="btn btn-xs btn-warning btn-flat">Riwayat Stock</button>';
                    $btn_stock = '<button type="button" onclick="updateForm(`' . route('stock.update', $id_stock) . '`)" class="btn btn-xs btn-success btn-flat">Update Stock</button>';
                }
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`' . route('produk.update', $produk->id_produk) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    '.$btn_stock.'
                    <button type="button" onclick="deleteData(`' . route('produk.destroy', $produk->id_produk) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    '.$btn_riwayat.'
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $kode = Produk::latest()->first();
        $produk = new Produk();

        $request->validate([
            'nama_produk' => 'required|unique:produk|max:255',
        ]);

        $kode_produk = 'P' . tambah_nol_didepan((int)$kode->id_produk + 1, 6);
        $harga_beli = customAngka($request->harga_beli);

        $harga_jual = customAngka($request->harga_jual);

        if ($harga_jual <= $harga_beli) {
            return response()->json('Data gagal disimpan', 400);
        }
        $produk->id_kategori = $request->id_kategori;
        $produk->kode_produk = $kode_produk;
        $produk->nama_produk = $request->nama_produk;
        $produk->merk = $request->merk;
        $produk->harga_jual = $harga_jual;
        $produk->save();
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
        
        $produk = Produk::find($id);
        $produk= new ProdukResource($produk);
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
        $produk = Produk::find($id);

        $harga_beli = customAngka($request->harga_beli);
        $harga_jual = customAngka($request->harga_jual);

        $request['harga_jual'] = $harga_jual;


        if ($harga_jual <= $harga_beli) {
            return response()->json('Data gagal disimpan', 400);
        }

        $produk->nama_produk = $request->nama_produk;
        $produk->merk = $request->merk;
        $produk->id_kategori = $request->id_kategori;
        $produk->harga_jual = $request->harga_jual;
        $produk->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
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

    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $dataproduk[] = $produk;
        }

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }
}
