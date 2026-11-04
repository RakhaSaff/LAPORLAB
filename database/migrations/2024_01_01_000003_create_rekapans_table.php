<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel rekapans — arsip rekap laporan per bulan.
     * Data di sini dipakai untuk laporan pimpinan Saintek.
     *
     * Relasi: satu rekap bulanan memiliki banyak laporan (via laporan_id).
     * Data utama sudah di tabel laporans (soft-deleted), rekap ini untuk agregasi.
     */
    public function up(): void
    {
        Schema::create('rekapans', function (Blueprint $table) {
            $table->id();

            // Periode rekap
            $table->string('bulan', 20)
                  ->comment('Nama bulan, contoh: Januari');
            $table->year('tahun')
                  ->comment('Tahun rekap, contoh: 2024');

            // Statistik agregat bulan tersebut
            $table->unsignedInteger('total_laporan')->default(0);
            $table->unsignedInteger('total_selesai')->default(0);
            $table->unsignedInteger('total_ditolak')->default(0);
            $table->unsignedInteger('total_menunggu')->default(0);
            $table->unsignedInteger('total_diproses')->default(0);

            // Relasi ke laporan spesifik (detail per baris rekap)
            $table->foreignId('laporan_id')->nullable()->constrained('laporans')->onDelete('set null')
                  ->comment('FK ke laporans — laporan yang masuk rekap ini');

            // Dibuat oleh siapa
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null')
                  ->comment('FK ke admins — stakeholder yang membuat rekap');

            $table->text('catatan')->nullable()
                  ->comment('Catatan tambahan untuk laporan pimpinan');

            $table->timestamps();

            // Index untuk query cepat per periode
            $table->index(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekapans');
    }
};
