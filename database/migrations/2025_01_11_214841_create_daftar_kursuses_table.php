<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarKursusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_kursus', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('id_kursus');
            $table->string('nama_kursus');
            $table->string('kategori'); // Kategori
            $table->text('deskripsi')->nullable(); // Deskripsi kursus
            $table->string('foto'); // Path/URL gambar
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
        Schema::dropIfExists('daftar_kursus');
    }
}
