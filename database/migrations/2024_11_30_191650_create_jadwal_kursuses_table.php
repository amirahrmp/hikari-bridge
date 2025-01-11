<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalKursusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_kursus', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('id_kursus');
            $table->integer('id_teacher');
            $table->date('hari'); 
            $table->time('waktu');
            $table->enum('tipe_kursus', ['Online', 'Offline']);
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
        Schema::dropIfExists('jadwal_kursus');
    }
}
