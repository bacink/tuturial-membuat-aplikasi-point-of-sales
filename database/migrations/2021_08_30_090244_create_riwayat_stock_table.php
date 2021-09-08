<?php

use App\Models\StockRiwayat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(((new StockRiwayat())->getTable()), function (Blueprint $table) {
            $table->increments('id_riwayat_stock');
            $table->unsignedInteger('id_stock');
            $table->integer('old_stock');
            $table->integer('qty');
            $table->string('symbol');
            $table->enum('sumber',['pembelian','penjualan','adjust']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_stock')->references('id_stock')->on('stock');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_stock');
    }
}
