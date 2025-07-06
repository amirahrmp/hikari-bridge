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
        Schema::create('spp_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->string('registration_type');
            $table->unsignedBigInteger('id_anak');
            $table->string('full_name');
            $table->string('program');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('nominal_uang_spp', 15, 2)->default(0);
            $table->string('status')->default('belum_bayar');
            $table->foreignId('user_id')->constrained('users');
            $table->text('notes')->nullable();
            $table->string('bukti_bayar_path')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();

            $table->unique(['registration_id', 'registration_type', 'bulan', 'tahun'], 'spp_bill_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_bills');
    }
};