<?php

use App\Models\Pembayaran;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PembayaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Pembayaran())->getTable(), function (Blueprint $table) {
            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_penjualan');
            $table->integer('piutang');
            $table->integer('bayar');
            $table->integer('sisa');
            $table->timestamps();
            $table->foreign('id_penjualan')->references('id_penjualan')->on('penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new Pembayaran())->getTable());
    }
}
