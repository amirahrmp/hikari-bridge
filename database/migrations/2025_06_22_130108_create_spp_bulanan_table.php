<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_bulanan', function (Blueprint $table) {
            $table->id();
            // Kolom untuk identifikasi pendaftaran asli (polimorfik)
            $table->unsignedBigInteger('registration_id');
            $table->string('registration_type');

            // Kolom data yang kita "salin" agar mudah ditampilkan
            $table->string('nama_lengkap');
            $table->string('program');
            $table->string('paket');

            // Kolom untuk tagihan
            $table->integer('bulan'); // 1-12
            $table->integer('tahun'); // contoh: 2025
            $table->decimal('nominal', 15, 2);
            $table->string('status')->default('belum_bayar'); // 'belum_bayar', 'lunas', 'menunggu_verifikasi'
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['registration_id', 'registration_type']);
            $table->unique(['registration_id', 'registration_type', 'bulan', 'tahun']); // Mencegah duplikat untuk anak yang sama di bulan yang sama
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_bulanan');
    }
};