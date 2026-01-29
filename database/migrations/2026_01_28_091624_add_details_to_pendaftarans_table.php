<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        // Menambahkan kolom baru setelah kolom email
        $table->string('agama')->nullable()->after('email');
        $table->string('provinsi')->nullable()->after('agama');
        $table->text('alamat')->nullable()->after('provinsi');
    });
}

public function down()
{
    Schema::table('pendaftarans', function (Blueprint $table) {
        $table->dropColumn(['agama', 'provinsi', 'alamat']);
    });
}
};
