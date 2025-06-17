<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatanTambahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_tambahan', function (Blueprint $table) {
            $table->id(); // Primary Key, Auto-increment
            $table->unsignedInteger('id_anak'); // Asumsi id_anak adalah string, sesuaikan jika integer
            $table->foreign('id_anak')->references('id_anak')->on('peserta_hikari_kidz')->onDelete('cascade');
            $table->string('nama_kegiatan'); // Nama kegiatan yang diinput
            $table->decimal('biaya'); // Biaya kegiatan, dengan 10 digit total, 2 di belakang koma
            $table->text('deskripsi')->nullable(); // Deskripsi kegiatan, bisa kosong
            $table->enum('status_pembayaran', ['belum', 'lunas'])->default('belum'); // Status pembayaran
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
        Schema::dropIfExists('kegiatan_tambahan');
    }
}
