<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade');
            $table->string('nama_teknisi');
            $table->date('tanggal_perbaikan');
            $table->string('barang_diperbaiki');
            $table->text('keterangan');
            $table->enum('status_perbaikan', ['diproses', 'selesai'])->default('diproses');
            $table->string('bukti_foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};