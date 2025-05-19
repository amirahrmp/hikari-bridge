<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_paket', 15)->unique();
            $table->string('nama_paket', 255);
            $table->string('durasi_jam', 15);
            $table->string('u_pendaftaran', 15);
            $table->string('u_pangkal', 15);
            $table->string('u_kegiatan', 15);
            $table->string('u_spp', 15);
            $table->string('u_makan', 15);
            $table->enum('tipe', ['Bulanan', 'Harian']);
            $table->string('biaya_penitipan', 15);
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
        Schema::dropIfExists('paket');
    }
}
