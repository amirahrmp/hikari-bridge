<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengasuhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengasuh', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_card', 10);
            $table->string('nik', 30);
            $table->string('nama_pengasuh', 50);
            $table->string('tipe_pengasuh', 50);
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
        Schema::dropIfExists('pengasuh');
    }
}
