<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalHikariKidzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_hikari_kidz', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('id_hikari_kidz');
            $table->integer('id_pengasuh');
            $table->date('hari'); 
            $table->time('waktu');
            $table->enum('tipe_hikari_kidz', ['Online', 'Offline']);
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
        Schema::dropIfExists('jadwal_hikari_kidz');
    }
}
