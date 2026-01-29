<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe kolom status menjadi VARCHAR(50) agar bisa menampung 'belum', 'aktif', 'selesai'
        // Default kita set 'belum'
        DB::statement("ALTER TABLE magangs MODIFY COLUMN status VARCHAR(50) DEFAULT 'belum'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opsional: Kembalikan ke enum jika rollback (sesuaikan dengan enum awal Anda jika ingat)
        // DB::statement("ALTER TABLE magangs MODIFY COLUMN status ENUM('active','inactive')");
    }
};