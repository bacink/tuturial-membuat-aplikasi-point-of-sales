<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produk = Produk::latest()->first() ?? new Produk();

        $produk = [
                [
                    'id_kategori' => 1,
                    'kode_produk'=> 'P'. tambah_nol_didepan((int)1, 6),
                    'nama_produk'=>'Runing',
                    'merk'=>'adidas',
                    'harga_jual'=>25000,
                ],
                [
                    'id_kategori' => 1,
                    'kode_produk'=> 'P'. tambah_nol_didepan((int)2, 6),
                    'nama_produk'=>'Soccer',
                    'merk'=>'adidas',
                    'harga_jual'=>35000,
                ],
            ];
            Produk::insert($produk);
        }
}
