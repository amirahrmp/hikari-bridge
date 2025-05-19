<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHkdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hkd', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_hikari_kidz', 15)->unique();
            $table->string('nama_hikari_kidz', 50);
            $table->string('jenis_hikari_kidz', 50);
            $table->string('level', 50);
            $table->string('kategori', 50);
            $table->string('kelas', 50);
            $table->string('kapasitas', 50);
            $table->string('waktu', 50);
            $table->string('biaya', 15);
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
        Schema::dropIfExists('hkds');
    }
}
