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
            $table->Increments('id');
            $table->foreignId('id_peserta')->constrained('peserta_kursus')->onDelete('cascade');
            $table->foreignId('id_kursus')->constrained('kursus')->onDelete('cascade');
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
