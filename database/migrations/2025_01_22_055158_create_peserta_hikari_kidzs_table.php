<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesertaHikariKidzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peserta_hikari_kidz', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_anak', 10)->unique();
            $table->string('nama_anak', 50);
            $table->string('nama_ortu', 50);
            $table->string('alamat', 100);
            $table->enum('jk', ['L', 'P']);
            $table->string('telp', 15)->nullable();
            $table->string('email', 50);
            $table->string('tmp_lahir', 50)->nullable();
            $table->date('tgl_lahir')->nullable();
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
        Schema::dropIfExists('peserta_hikari_kidz');
    }
}
