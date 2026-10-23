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
        Schema::table('pesans', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('voice_path');
            $table->boolean('is_starred')->default(false)->after('is_pinned');
            $table->unsignedBigInteger('pinned_by')->nullable()->after('is_starred');
            $table->unsignedBigInteger('starred_by')->nullable()->after('pinned_by');
            $table->foreign('pinned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('starred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->dropColumn('is_pinned', 'is_starred', 'pinned_by', 'starred_by');
        });
    }
};
