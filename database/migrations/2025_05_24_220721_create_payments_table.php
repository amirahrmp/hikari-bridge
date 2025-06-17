<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('payments', function (Blueprint $table) {
        
    $table->id();
    $table->unsignedBigInteger('registration_id');
    $table->string('registration_type'); // kidzclub, quran, daycare
    $table->string('komponen');
    $table->integer('jumlah');
    $table->string('bukti_transfer')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

class AddTotalToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('jumlah')->after('registration_type')->default(0);
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });
    }
}
