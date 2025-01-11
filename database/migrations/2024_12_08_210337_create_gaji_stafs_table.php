<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGajiStafsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gaji_staf', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('id_gaji')->unique(); // Kode ID Gaji seperti GJ-YYYYMMDD-RANDOM
            $table->date('tgl_gaji');
            $table->decimal('total_gaji', 15, 2); // Total gaji yang dibayarkan
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
        Schema::dropIfExists('gaji_staf');
    }
}
