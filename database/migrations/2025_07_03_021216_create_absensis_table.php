<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->string('registration_type');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('full_name');
            $table->time('jam_datang');
            $table->time('jam_pulang')->nullable();
            // Jika Anda menyimpan durasi_hadir dan overtime sebagai kolom:
            // $table->string('durasi_hadir')->nullable();
            // $table->string('overtime')->nullable();
            $table->decimal('denda', 10, 2)->default(0); // Denda otomatis
            $table->timestamps();

            // Foreign Key Constraints (pastikan tabel ini ada)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('registration_id')->references('id')->on('registration_hikari_kidz_daycares')->onDelete('cascade');
            // Sesuaikan 'registration_hikari_kidz_daycares' dengan nama tabel pendaftaran Daycare Anda yang sebenarnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};