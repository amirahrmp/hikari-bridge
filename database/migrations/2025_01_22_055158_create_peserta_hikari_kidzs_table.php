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
            $table->increments('id_anak'); // Ini akan auto increment mulai dari 1
            $table->string('full_name', 255);
            $table->string('nickname', 255);
            $table->date('birth_date');
            $table->string('parent_name', 255);
            $table->string('address', 255);
            $table->string('whatsapp_number', 15)->nullable();
            $table->string('tipe', 255); //yang bakal di ambil tipe nya
            $table->string('file_upload'); 
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
