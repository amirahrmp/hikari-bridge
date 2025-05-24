<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalMakanDaycaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_makan_daycare', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('hari', 20);
            $table->string('snack_pagi', 255);
            $table->string('makan_siang', 255); 
            $table->string('snack_sore', 255);
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
        Schema::dropIfExists('jadwal_makan_daycare');
    }
}
