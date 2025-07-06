<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCicilanUangPangkalPlanToRegistrationHikariKidzDaycaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('registration_hikari_kidz_daycares', function (Blueprint $table) {
        $table->unsignedTinyInteger('cicilan_uang_pangkal_plan')->nullable(); // Hapus ->after('paket_id')
    });
}

public function down()
{
    Schema::table('registration_hikari_kidz_daycares', function (Blueprint $table) {
        $table->dropColumn('cicilan_uang_pangkal_plan');
    });
}
}
