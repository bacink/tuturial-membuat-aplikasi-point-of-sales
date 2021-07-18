<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kategori = [
            [
                'nama_kategori'=>'sepatu'
            ],
            [
                'nama_kategori'=>'baju'
            ],
        ];
        Kategori::insert($kategori);
    }
}