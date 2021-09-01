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
            $table->unsignedInteger('id_member');
            $table->unsignedInteger('id_produk');
            $table->integer('harga_jual');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_member')->references('id_member')->on('member');
            $table->foreign('id_produk')->references('id_produk')->on('produk');
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
