<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = array(
            [
                'nama' => 'aulia zulfhatun zahra',
                'alamat' => 'palasari',
                'telepon' => '087879012438',
            
            ],
            [
                'nama' => 'aulia khaliluna zahra',
                'alamat' => 'aulia khaliluna zahra',
                'telepon' => '087879012438',
            ]
        );

        array_map(function (array $user) {
            Supplier::query()->updateOrCreate(
                $user
            );
        }, $suppliers);
    }
}
