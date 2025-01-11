<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailGajiStafsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_gaji_staf', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedBigInteger('id_gaji'); // Relasi dengan tabel gaji_staf
            $table->unsignedBigInteger('id_staf'); // Relasi dengan tabel staf (staf yang mendapat gaji)
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('uang_makan', 15, 2);
            $table->decimal('tunjangan', 15, 2);
            $table->decimal('uang_transport', 15, 2);
            $table->decimal('bonus', 15, 2);
            $table->decimal('potongan_pph21', 15, 2);
            $table->decimal('potongan', 15, 2);
            $table->decimal('total_gaji', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_gaji_staf');
    }
}
