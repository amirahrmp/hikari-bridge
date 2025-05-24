<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationHikariKidzDaycareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_hikari_kidz_daycares', function (Blueprint $table) {
            $table->Increments('id');
            $table->bigInteger('id_anak', 20)->unique()->nullable();
            $table->string('full_name', 255);
            $table->string('nickname', 255);
            $table->date('birth_date');
            $table->integer('child_order'); // Anak ke-berapa
            $table->string('file_upload');
            $table->integer('siblings_count'); // Jumlah saudara
            $table->integer('height_cm'); // Tinggi badan dalam cm
            $table->integer('weight_kg'); // Berat badan dalam kg
            $table->string('parent_name', 255);
            $table->string('parent_job', 255);
            $table->string('whatsapp_number', 15);
            $table->text('address');
            $table->string('age_group', 50);
            $table->string('package_type', 50);
            $table->text('medical_history')->nullable();
            $table->string('eating_habit', 50);
            $table->string('favorite_food', 255);
            $table->string('favorite_drink', 255);
            $table->string('favorite_toy', 255);
            $table->text('specific_habits')->nullable();
            $table->string('caretaker', 255);
            $table->boolean('trial_agreement');
            $table->date('trial_date')->nullable();
            $table->date('start_date');
            $table->string('reason_for_choosing', 255);
            $table->string('information_source', 255);
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
        Schema::dropIfExists('registration_hikari_kidz_daycares');
    }

}