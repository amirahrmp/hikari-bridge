<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketHqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket_hq', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_pakethq', 15)->unique();
            $table->string('kelas', 255);
            $table->string('kapasitas', 255);
            $table->string('durasi', 255);
            $table->string('u_pendaftaran', 15);
            $table->string('u_modul', 15);
            $table->string('u_spp', 15);
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
        Schema::dropIfExists('paket_hq');
    }
}
