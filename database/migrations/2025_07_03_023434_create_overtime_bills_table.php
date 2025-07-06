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
        Schema::create('overtime_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id'); // ID dari tabel pendaftaran
            $table->string('registration_type'); // Nama model pendaftaran (polymorphic relation)
            $table->unsignedBigInteger('id_anak'); // ID Anak dari peserta_hikari_kidz
            $table->string('full_name');
            $table->string('program');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('total_overtime_minutes')->default(0);
            $table->decimal('total_denda', 15, 2)->default(0); // Denda dalam Rupiah
            $table->string('status')->default('belum_bayar'); // belum_bayar, sudah_bayar
            $table->foreignId('user_id')->constrained('users'); // Foreign key ke tabel users
            $table->text('notes')->nullable();
            $table->timestamps();

            // Tambahkan unique constraint agar tidak ada duplikat tagihan per bulan per pendaftaran
            $table->unique(['registration_id', 'registration_type', 'bulan', 'tahun'], 'overtime_bill_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_bills');
    }
};