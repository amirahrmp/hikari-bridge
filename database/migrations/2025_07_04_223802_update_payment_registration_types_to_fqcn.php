<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Payment; // Import model Payment

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records in the 'payments' table
        Payment::where('registration_type', 'Hikari Kidz Club')
               ->update(['registration_type' => \App\Models\RegistrationHikariKidzClub::class]);

        Payment::where('registration_type', 'Hikari Kidz Daycare')
               ->update(['registration_type' => \App\Models\RegistrationHikariKidzDaycare::class]);

        // Tambahkan jika ada tipe lain yang perlu diubah dari string literal ke FQCN
        // Payment::where('registration_type', 'Hikari Quran')
        //        ->update(['registration_type' => \App\Models\RegistrationHikariQuran::class]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back for rollback (optional, but good practice)
        // HATI-HATI: Jika Anda roll back, data yang baru dibuat setelah ini akan kehilangan FQCN
        // dan kembali ke string literal yang lama.
        Payment::where('registration_type', \App\Models\RegistrationHikariKidzClub::class)
               ->update(['registration_type' => 'Hikari Kidz Club']);

        Payment::where('registration_type', \App\Models\RegistrationHikariKidzDaycare::class)
               ->update(['registration_type' => 'Hikari Kidz Daycare']);

        // Payment::where('registration_type', \App\Models\RegistrationHikariQuran::class)
        //        ->update(['registration_type' => 'Hikari Quran']);
    }
};