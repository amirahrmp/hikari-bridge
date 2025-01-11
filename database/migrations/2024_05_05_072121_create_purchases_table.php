<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_bahanbaku',6)->unique();
            $table->date('tanggal_beli');
            $table->string('nama_bahanbaku',50);
            $table->string('harga',50);
            $table->string('quantity',50);
            $table->string('total',50);
            $table->string('nama_perusahaan',50);
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
        Schema::dropIfExists('purchase');
    }
}
