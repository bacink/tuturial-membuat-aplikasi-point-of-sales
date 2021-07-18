<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahForeginKeyToHargaMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('harga_member', function (Blueprint $table) {
            $table->unsignedInteger('id_member')->change();
            
            $table->foreign('id_member')
            ->references('id_member')
            ->on('member')
            ->onUpdate('restrict')
            ->onDelete('restrict');
            
            $table->unsignedInteger('id_produk')->change();
            $table->foreign('id_produk')
            ->references('id_produk')
            ->on('produk')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('harga_member', function (Blueprint $table) {
            $table->integer('id_member')->change();
            $table->dropForeign('member_id_member_foreign');

            $table->integer('id_produk')->change();
            $table->dropForeign('produk_id_produk_foreign');
        });
    }
}
