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
        Schema::table('pendaftarans', function (Blueprint $table) {
            // TAMBAHKAN BARIS INI
            // Ini akan membuat kolom user_id yang terhubung ke tabel 'users'
            // Dibuat 'nullable' agar data lama tidak error
            // Dibuat 'onDelete('set null')' agar jika user dihapus, data pendaftarannya tetap ada
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            // TAMBAHKAN DUA BARIS INI
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};