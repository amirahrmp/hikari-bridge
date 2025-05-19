<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationProgramHkcwsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_program_hkcw', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('full_name'); 
            $table->string('nama_panggilan');
            $table->string('parent_name'); 
            $table->string('whatsapp_number'); // Nomor Telepon
            $table->text('address'); // Alamat
            $table->string('kelas');
            $table->string('tipe');
            $table->string('bukti_bayar');
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
        Schema::dropIfExists('registration_program_hkcw');
    }
}
