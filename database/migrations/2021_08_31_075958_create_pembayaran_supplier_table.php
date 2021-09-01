<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaranSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran_supplier', function (Blueprint $table) {
            $table->increments('id_pembayaran_supplier');
            $table->unsignedInteger('id_pembelian');
            $table->integer('piutang');
            $table->integer('bayar');
            $table->integer('sisa');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayaran_supplier');
    }
}
