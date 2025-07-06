<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaAnakDanPaketToLaporanPemasukan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laporan_pemasukan', function (Blueprint $table) {
            $table->string('nama_anak')->nullable();
            $table->string('nama_paket_spesifik')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laporan_pemasukan', function (Blueprint $table) {
            //
        });
    }
}
