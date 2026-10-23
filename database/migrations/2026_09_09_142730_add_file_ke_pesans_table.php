<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->text('isi_pesan')->nullable()->change();
            $table->string('file_path')->nullable()->after('isi_pesan');
            $table->string('file_nama')->nullable()->after('file_path');
            $table->string('file_tipe')->nullable()->after('file_nama');
        });
    }

    public function down(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->text('isi_pesan')->nullable(false)->change();
            $table->dropColumn(['file_path', 'file_nama', 'file_tipe']);
        });
    }
};