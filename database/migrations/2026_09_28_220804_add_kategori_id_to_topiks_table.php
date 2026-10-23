<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('topiks', function (Blueprint $table) {
            $table->foreignId('kategori_id')
                ->nullable()
                ->after('id')
                ->constrained('kategori_konsultans')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('topiks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
        });
    }
};