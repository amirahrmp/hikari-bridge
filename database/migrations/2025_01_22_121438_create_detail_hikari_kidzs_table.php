<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailHikariKidzsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_hikari_kidz', function (Blueprint $table) {
            $table->id(); // Primary key otomatis
            $table->foreignId('id_anak')->constrained('peserta_hikari_kidz')->onDelete('cascade');
            $table->foreignId('id_hikari_kidz')->constrained('hikari_kidz')->onDelete('cascade');
            $table->timestamps();
        
            // Unique constraint untuk pasangan id_anak dan id_hikari_kidz
            $table->unique(['id_anak', 'id_hikari_kidz'], 'unique_participant_course');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_hikari_kidz');
    }
}
