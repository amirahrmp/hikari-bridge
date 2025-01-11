<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi_teacher', function (Blueprint $table) {
            $table->Increments('id_presensi_teacher');
            $table->string('id_card', 20);
            $table->string('nama_teacher'); 
            $table->enum('keterangan', ['Hadir', 'Izin', 'Alfa']);
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_keluar')->nullable();

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
        Schema::dropIfExists('presensi_teacher');
    }
}
