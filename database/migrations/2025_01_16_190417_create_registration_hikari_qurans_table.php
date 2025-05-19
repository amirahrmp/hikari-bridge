<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationHikariQuransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_hikari_quran', function (Blueprint $table) {
            $table->id();
            $table->string('full_name'); 
            $table->string('nickname', 255);
            $table->date('birth_date');
            $table->string('file_upload');
            $table->string('parent_name'); 
            $table->string('whatsapp_number'); // Nomor Telepon
            $table->text('address'); // Alamat
            $table->string('kelas');
            $table->enum('tipe', ['online', 'offline'])->nullable();
            $table->enum('sumberinfo', ['facebook', 'instagram', 'whatsapp', 'teman', 'kantor', 'spanduk', 'brosur', 'tetangga', 'other'])->nullable();
            $table->string('promotor'); 
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
        Schema::dropIfExists('registration_hikari_quran');
    }
}
