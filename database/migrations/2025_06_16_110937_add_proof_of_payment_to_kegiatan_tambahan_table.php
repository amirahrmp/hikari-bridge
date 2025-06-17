<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProofOfPaymentToKegiatanTambahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan_tambahan', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            $table->string('payment_method')->nullable()->after('bukti_pembayaran');
            $table->timestamp('payment_date')->nullable()->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('kegiatan_tambahan', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_date');
        });
    }
}
