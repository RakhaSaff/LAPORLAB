<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perbaikans', function (Blueprint $table) {
            $table->string('kode_inv')->nullable()->after('barang_diperbaiki');
        });
    }

    public function down(): void
    {
        Schema::table('perbaikans', function (Blueprint $table) {
            $table->dropColumn('kode_inv');
        });
    }
};
