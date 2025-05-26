<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('payments', function (Blueprint $table) {
        
    $table->id();
    $table->unsignedBigInteger('registration_id');
    $table->string('registration_type'); // kidzclub, quran, daycare
    $table->string('komponen');
    $table->integer('jumlah');
    $table->string('bukti_transfer')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};