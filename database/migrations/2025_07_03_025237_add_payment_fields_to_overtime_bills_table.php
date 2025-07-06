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
        Schema::table('overtime_bills', function (Blueprint $table) {
            $table->string('bukti_bayar_path')->nullable()->after('status');
            $table->timestamp('tanggal_bayar')->nullable()->after('bukti_bayar_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_bills', function (Blueprint $table) {
            $table->dropColumn(['bukti_bayar_path', 'tanggal_bayar']);
        });
    }
};