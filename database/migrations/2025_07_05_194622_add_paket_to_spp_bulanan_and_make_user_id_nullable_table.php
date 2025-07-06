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
        Schema::table('spp_bulanan', function (Blueprint $table) {
            // Tambahkan kolom 'paket' jika belum ada
            if (!Schema::hasColumn('spp_bulanan', 'paket')) {
                $table->string('paket')->nullable()->after('program'); // Tambahkan setelah 'program'
            }
            // Ubah kolom 'user_id' menjadi nullable jika belum
            // Pastikan tipe data sesuai (unsignedBigInteger)
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spp_bulanan', function (Blueprint $table) {
            // Hapus kolom 'paket' jika di-rollback
            if (Schema::hasColumn('spp_bulanan', 'paket')) {
                $table->dropColumn('paket');
            }
            // Ubah kembali kolom 'user_id' menjadi not nullable jika di-rollback
            // Hanya jika Anda memang ingin user_id kembali NOT NULL
            // Anda mungkin perlu mengetahui apakah user_id aslinya nullable atau tidak
            // Jika ingin tetap nullable, Anda bisa menghapus baris ini
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};