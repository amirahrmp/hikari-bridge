<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailJadwalHikariKidzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_jadwal_hikari_kidz', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('id_jadwal_hikari_kidz');
            $table->integer('id_anak');
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
        Schema::dropIfExists('detail_jadwal_hikari_kidz');
    }
}
