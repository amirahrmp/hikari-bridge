<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('peserta_id')->nullable();
            $table->string('nama_anak');
            $table->date('tanggal');
            $table->json('kegiatan');
            $table->text('catatan')->nullable();
            $table->string('foto')->nullable();
            $table->enum('tipe', ['HKD', 'HKC'])->default('HKD');
            $table->timestamps();

            $table->foreign('peserta_id')
                ->references('id_anak')
                ->on('peserta_hikari_kidz')
                ->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan_kegiatan');
    }
}
