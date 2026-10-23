<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->timestamp('dibersihkan_audiens_pada')->nullable()->after('disembunyikan_oleh_konsultan');
            $table->timestamp('dibersihkan_konsultan_pada')->nullable()->after('dibersihkan_audiens_pada');
        });
    }

    public function down(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn(['dibersihkan_audiens_pada', 'dibersihkan_konsultan_pada']);
        });
    }
};