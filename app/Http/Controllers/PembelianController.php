<?php

namespace App\Http\Controllers;

use App\Models\PembayaranSupplier;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Traits\StockRiwayat;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    use StockRiwayat;

    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();

        return view('pembelian.index', compact('supplier'));
    }

    public function data()
    {
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->get();

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()
            ->addColumn('total_item', function ($pembelian) {
                return format_uang($pembelian->total_item);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->bayar);
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama;
            })
       
            ->addColumn('aksi', function ($pembelian) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('pembelian.show', $pembelian->id_pembelian) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('pembelian.destroy', $pembelian->id_pembelian) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $pembelian = new Pembelian();
        $pembelian->id_supplier = $id;
        $pembelian->total_item  = 0;
        $pembelian->total_harga = 0;
        $pembelian->save();

        session(['id_pembelian' => $pembelian->id_pembelian]);
        session(['id_supplier' => $pembelian->id_supplier]);

        return redirect()->route('pembelian_detail.index');
    }

    public function store(Request $request)
    {
        $pembelian = Pembelian::findOrFail($request->id_pembelian);

        DB::transaction(function () use ($request,$pembelian) {
            $pembelian->total_item = $request->total_item;
            $pembelian->total_harga = $request->total;  
            $pembelian->update();

            $pembayaran = new PembayaranSupplier();
            $pembayaran->id_pembelian = $request->id_pembelian;
            $pembayaran->piutang = $request->total;
            $pembayaran->bayar = $request->bayar;
            $pembayaran->sisa = customAngka($request->sisa);
            $pembayaran->save();

            $detail = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        
            foreach ($detail as $item) {
                
                $cekStock = Stock::whereIdProduk($item->id_produk)->first();

                if($cekStock){
                    $cekStock->qty = $cekStock->qty + $item->jumlah;
                    $cekStock->update();

                    $deskripsi = 'Diperoleh dari Belanja';
                    $this->catatRiwayat($cekStock->id_stock,$item->jumlah,$deskripsi);
                    
                }else{
                    $stock = new Stock();
                    $stock->id_produk = 
                    $stock->qty = $item->jumlah;
                    $stock->save();
                    $deskripsi = 'Diperoleh dari Belanja';
                    
                    $this->catatRiwayat($stock->id_stock,$item->jumlah,$deskripsi);
                    
                }

            }
        }); 

        return redirect()->route('pembelian.index');
    }

    public function show($id)
    {
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_beli);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $detail    = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }
}
