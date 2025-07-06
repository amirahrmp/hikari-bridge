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
            if (!Schema::hasColumn('payments', 'spp_bill_id')) {
                $table->unsignedBigInteger('spp_bill_id')->nullable()->after('overtime_bill_id');
                $table->foreign('spp_bill_id')->references('id')->on('spp_bills')->onDelete('set null');
                $table->unique(['spp_bill_id'], 'payments_spp_bill_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'spp_bill_id')) {
                $table->dropForeign(['spp_bill_id']);
                $table->dropUnique('payments_spp_bill_unique');
                $table->dropColumn('spp_bill_id');
            }
        });
    }
};