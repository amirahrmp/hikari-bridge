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
            $table->id();
            $table->string('tipe_daycare')->nullable(); // hanya diisi jika program = Daycare
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('kegiatan');
            $table->text('keterangan')->nullable();
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
