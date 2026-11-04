<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Nama barang dari Master Data bisa lebih panjang dari 30 karakter
     * (mis. "Gas Chromatograph Mass Spectrometer Single Quadrupole"),
     * jadi kolom di tabel laporans dilebarkan agar tidak error saat disimpan.
     */
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('nama_barang', 150)->change();
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('nama_barang', 30)->change();
        });
    }
};