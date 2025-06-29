<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToRegistrationHikariKidzDaycaresTable extends Migration
{
    public function up()
    {
        Schema::table('registration_hikari_kidz_daycares', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id_anak')->nullable();
            // jangan tambahkan foreign key constraint dulu
        });
    }

    public function down()
    {
        Schema::table('registration_hikari_kidz_daycares', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
