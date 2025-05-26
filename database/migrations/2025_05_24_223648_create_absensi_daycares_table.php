<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiDaycaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi_daycare', function (Blueprint $table) {
            $table->id();
            $table->increments('id_anak')->constrained('peserta_hikari_kidz')->onDelete('cascade');
            $table->time('jam_datang');
            $table->time('jam_pulang')->nullable();
            $table->integer('overtime')->nullable(); // dalam menit
            $table->string('denda')->nullable();    // dalam rupiah
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
        Schema::dropIfExists('absensi_daycare');
    }
}
