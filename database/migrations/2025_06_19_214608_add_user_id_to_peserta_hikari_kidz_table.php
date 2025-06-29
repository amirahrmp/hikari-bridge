<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToPesertaHikariKidzTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peserta_hikari_kidz', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peserta_hikari_kidz', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Hapus foreign key terlebih dahulu
            $table->dropColumn('user_id');   // Kemudian hapus kolom
        });
    }
}
