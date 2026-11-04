<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel notifikasis — log notifikasi perubahan status laporan.
     * Pengguna mendapat notifikasi saat laporan mereka diverifikasi,
     * ditugaskan ke teknisi, atau selesai diperbaiki.
     *
     * Tabel ini mendukung fitur "Laporan Saya" di class diagram —
     * pengguna dapat memantau update status laporan mereka.
     */
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            // Penerima notifikasi
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')
                  ->comment('FK ke users — penerima notifikasi (pelapor)');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade')
                  ->comment('FK ke admins — penerima notifikasi (teknisi/stakeholder)');

            // Laporan terkait
            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade')
                  ->comment('FK ke laporans — laporan yang memicu notifikasi');

            // Isi notifikasi
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', [
                'laporan_masuk',
                'laporan_diverifikasi',
                'laporan_ditolak',
                'laporan_diproses',
                'laporan_selesai',
                'penugasan_teknisi'
            ]);

            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('dibaca_pada')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'sudah_dibaca']);
            $table->index(['admin_id', 'sudah_dibaca']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
