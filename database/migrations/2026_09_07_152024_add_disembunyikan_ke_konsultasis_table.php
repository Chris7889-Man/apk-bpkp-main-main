<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->boolean('disembunyikan_oleh_audiens')->default(false)->after('status');
            $table->boolean('disembunyikan_oleh_konsultan')->default(false)->after('disembunyikan_oleh_audiens');
        });
    }

    public function down(): void
    {
        Schema::table('konsultasis', function (Blueprint $table) {
            $table->dropColumn(['disembunyikan_oleh_audiens', 'disembunyikan_oleh_konsultan']);
        });
    }
};