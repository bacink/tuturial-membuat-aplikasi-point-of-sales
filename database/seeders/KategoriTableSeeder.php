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
        $kategoris = [
            [
                'nama_kategori'=>'sepatu'
            ],
            [
                'nama_kategori'=>'baju'
            ],
        ];

        array_map(function (array $kategori) {
            Kategori::query()->updateOrCreate(
                $kategori
            );
        }, $kategoris);
    }
}