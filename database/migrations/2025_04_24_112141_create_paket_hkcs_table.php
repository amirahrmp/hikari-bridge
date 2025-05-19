<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketHkcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket_hkc', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_pakethkc', 15)->unique();
            $table->string('member', 255);
            $table->string('kelas', 255);
            $table->string('u_pendaftaran', 15);
            $table->string('u_perlengkapan', 15);
            $table->string('u_sarana', 15);
            $table->string('u_spp', 15);
            $table->enum('tipe', ['Bulanan', 'Harian']);
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
        Schema::dropIfExists('paket_hkc');
    }
}
