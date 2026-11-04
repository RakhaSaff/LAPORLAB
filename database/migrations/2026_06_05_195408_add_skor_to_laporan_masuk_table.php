<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('laporans', function (Blueprint $table) {
        $table->integer('total_skor')->default(0)->after('status');
        $table->string('prioritas')->default('rendah')->after('total_skor');
    });
}

public function down(): void
{
    Schema::table('laporans', function (Blueprint $table) {
        $table->dropColumn(['total_skor', 'prioritas']);
    });
}
};