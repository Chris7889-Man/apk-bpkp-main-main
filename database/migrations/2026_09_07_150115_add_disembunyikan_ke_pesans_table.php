<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->boolean('disembunyikan_oleh_pengirim')->default(false)->after('isi_pesan');
        });
    }

    public function down(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->dropColumn('disembunyikan_oleh_pengirim');
        });
    }
};