<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangRefilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_refil', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('barang_id')->nullable();
            $table->bigInteger('stok')->nullable();
            $table->bigInteger('hb1')->nullable();
            $table->bigInteger('hb100')->nullable();
            $table->bigInteger('hb250')->nullable();
            $table->bigInteger('hb500')->nullable();
            $table->bigInteger('hb1l')->nullable();
            $table->bigInteger('hj1')->nullable();
            $table->bigInteger('hj100')->nullable();
            $table->bigInteger('hj250')->nullable();
            $table->bigInteger('hj500')->nullable();
            $table->bigInteger('hj1l')->nullable();
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
        Schema::dropIfExists('barang_refil');
    }
}
