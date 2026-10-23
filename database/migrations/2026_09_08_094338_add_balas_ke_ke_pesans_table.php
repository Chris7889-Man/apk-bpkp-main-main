<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->foreignId('balas_ke_id')->nullable()->after('pengirim_id')
                ->constrained('pesans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('balas_ke_id');
        });
    }
};