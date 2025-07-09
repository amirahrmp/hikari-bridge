<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailKursusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_kursus', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('id_peserta');
            $table->unsignedInteger('id_kursus');

            $table->foreign('id_peserta')->references('id')->on('peserta_kursus')->onDelete('cascade');
            $table->foreign('id_kursus')->references('id')->on('kursus')->onDelete('cascade');

            $table->timestamps();
            $table->unique(['id_peserta', 'id_kursus'], 'unique_participant_course');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_kursus');
    }
}
