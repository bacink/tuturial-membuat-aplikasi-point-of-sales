<?php

use App\Http\Controllers\{
    DashboardController,
    HargaMemberController,
    KategoriController,
    LaporanController,
    ProdukController,
    MemberController,
    NewPenjualanController,
    NewTransaksiController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    PiutangController,
    SettingController,
    SupplierController,
    UserController,
};
use App\Models\Produk;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        Route::get('/harga-member/data', [HargaMemberController::class, 'data'])->name('harga-member.data');
        Route::post('/harga-member/cetak-harga-member', [HargaMemberController::class, 'cetakHargaMember'])->name('harga-member.cetak_harga_member');
        Route::resource('/harga-member', HargaMemberController::class);

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::get('/member/selection', [MemberController::class, 'selection'])->name('member.selection');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/produk/{produk_id}/member/{member_id}', [ProdukController::class, 'member'])->name('produk.member');

        Route::get('/penjualan/{id}/kasir', [PenjualanController::class, 'data'])->name('penjualan.kasir');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');

        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');


        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');

        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');

        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');

        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');

        Route::name('new.transaksi.')->group(function () {
            Route::resource('/new/transaksi', NewTransaksiController::class)
                ->except('create', 'show', 'edit', 'delete');

            Route::prefix('new')->group(function () {
                Route::get('/transaksi/member/{id}', [NewTransaksiController::class, 'member'])->name('member');
                Route::get('/transaksi/detail/{id}', [NewTransaksiController::class, 'detail'])->name('detail');
                Route::get('/transaksi/{id}', [NewTransaksiController::class, 'show'])->name('show');
                Route::get('/transaksi/delete/{id}', [NewTransaksiController::class, 'delete'])->name('delete');
            });
        });

        Route::name('new.penjualan.')->group(function () {
            Route::resource('/new/penjualan', NewPenjualanController::class)
                ->except('create', 'show', 'edit');

            Route::prefix('new')->group(function () {
            });
        });

        Route::get('/piutang/data/', [PiutangController::class, 'data'])->name('piutang.data');
        Route::get('/piutang/{id}', [PiutangController::class, 'show'])->name('piutang.show');
        Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');

    });
});
