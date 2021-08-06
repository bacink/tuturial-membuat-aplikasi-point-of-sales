<?php

use App\Models\HargaMember;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new HargaMember())->getTable(), function (Blueprint $table) {
            $table->increments('id_harga_member');
            $table->integer('id_member');
            $table->integer('id_produk');
            $table->integer('harga_jual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new HargaMember())->getTable());
    }
}
