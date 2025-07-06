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
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('spp_bulanan', 'bukti_bayar_path')) {
                $table->string('bukti_bayar_path')->nullable()->after('status'); // Tambahkan setelah kolom 'status'
            }
            if (!Schema::hasColumn('spp_bulanan', 'tanggal_bayar')) {
                $table->timestamp('tanggal_bayar')->nullable()->after('bukti_bayar_path'); // Tambahkan setelah 'bukti_bayar_path'
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spp_bulanan', function (Blueprint $table) {
            // Hapus kolom jika ada
            if (Schema::hasColumn('spp_bulanan', 'bukti_bayar_path')) {
                $table->dropColumn('bukti_bayar_path');
            }
            if (Schema::hasColumn('spp_bulanan', 'tanggal_bayar')) {
                $table->dropColumn('tanggal_bayar');
            }
        });
    }
};