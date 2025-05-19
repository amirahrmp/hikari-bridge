<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationHikariKidzClubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_hikari_kidz_clubs', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255);
            $table->string('nickname', 255);
            $table->date('birth_date');
            $table->string('file_upload');
            $table->string('parent_name', 255); 
            $table->integer('whatsapp_number'); 
            $table->text('address');
            $table->enum('agama', ['islam', 'kristen', 'hindu', 'budha', 'konghucu']);
            $table->enum('nonmuslim', ['paket1', 'paket2'])->nullable();
            $table->enum('member', ['tetap', 'harian']);
            $table->enum('kelas', ['himawari', 'sakura', 'bara']);
            $table->string('promotor'); 
            // Menyimpan input "other" dari sumberinfo
            // $table->string('sumberinfo_other', 255)->nullable();
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
        Schema::dropIfExists('registration_hikari_kidz_club');
    }
}
