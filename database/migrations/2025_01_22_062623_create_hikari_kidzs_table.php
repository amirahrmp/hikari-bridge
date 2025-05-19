<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHikariKidzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hikari_kidz', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_hikari_kidz', 15)->unique();
            $table->string('nama_hikari_kidz', 50);
            $table->string('jenis_hikari_kidz', 50);
            $table->string('paket');
            $table->string('kelas', 50);
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
        Schema::dropIfExists('hikari_kidz');
    }
}
