<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->timestamp('dibaca_at')->nullable()->after('file_tipe');
        });
    }

    public function down()
    {
        Schema::table('pesans', function (Blueprint $table) {
            $table->dropColumn('dibaca_at');
        });
    }
};