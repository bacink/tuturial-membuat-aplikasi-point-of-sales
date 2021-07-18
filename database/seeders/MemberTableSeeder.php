<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $member = [
            [
                'kode_member'=> tambah_nol_didepan(1, 5),
                'nama'=>'Egi ariska',
                'alamat'=>'palasari',
                'telepon'=>'087879012438',
            ],
            [
              
                'kode_member'=> tambah_nol_didepan(2, 5),
                'nama'=>'Aulia Zulfhatun Zahra',
                'alamat'=>'palasari',
                'telepon'=>'087879012438',
            ],
        ];
        Member::insert($member);
    }
}
