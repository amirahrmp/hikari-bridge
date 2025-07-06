<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiHkcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi_hkc', function (Blueprint $table) {
            $table->id();
            $table->string('id_anak');
            $table->string('nama_anak');
            $table->string('nama_paket');
            $table->enum('keterangan', ['Hadir', 'Izin', 'Alfa']);
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
        Schema::dropIfExists('absensi_hkc');
    }
}
