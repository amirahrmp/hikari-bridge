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
        Schema::table('payments', function (Blueprint $table) {
            // Tambahkan spp_bulanan_id jika belum ada
            if (!Schema::hasColumn('payments', 'spp_bulanan_id')) {
                $table->unsignedBigInteger('spp_bulanan_id')->nullable()->after('user_id');
                $table->foreign('spp_bulanan_id')->references('id')->on('spp_bulanan')->onDelete('set null');
            }
            // Tambahkan overtime_bill_id jika belum ada
            if (!Schema::hasColumn('payments', 'overtime_bill_id')) {
                $table->unsignedBigInteger('overtime_bill_id')->nullable()->after('spp_bulanan_id'); // Sesuaikan posisi
                $table->foreign('overtime_bill_id')->references('id')->on('overtime_bills')->onDelete('set null');
            }

            // Tambahkan unique constraint jika diperlukan (hati-hati jika 1 payment bisa bayar banyak tagihan)
            // Jika 1 payment hanya untuk 1 spp_bulanan ATAU 1 overtime_bill, maka unique constraint ini cocok
            // Jika 1 payment bisa bayar banyak spp_bulanan, atau banyak overtime_bill, maka unique constraint ini tidak cocok.
            // Untuk kasus Anda yang ingin bayar per tagihan, ini cocok.
            $table->unique(['spp_bulanan_id'], 'payments_spp_bulanan_unique');
            $table->unique(['overtime_bill_id'], 'payments_overtime_bill_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'spp_bulanan_id')) {
                $table->dropForeign(['spp_bulanan_id']);
                $table->dropUnique('payments_spp_bulanan_unique');
                $table->dropColumn('spp_bulanan_id');
            }
            if (Schema::hasColumn('payments', 'overtime_bill_id')) {
                $table->dropForeign(['overtime_bill_id']);
                $table->dropUnique('payments_overtime_bill_unique');
                $table->dropColumn('overtime_bill_id');
            }
        });
    }
};