<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel laporans — inti laporan kerusakan fasilitas SMARTFAS Saintek.
     * Dibuat oleh pengguna (mahasiswa/dosen), diproses oleh stakeholder.
     *
     * Disempurnakan dari file asli Anda:
     *   + ditambah tingkat_kerusakan (dari class diagram, diisi AI)
     *   + ditambah prioritas_ai (skor 1–100 dari AI)
     *   + ditambah admin_id (FK ke admins, siapa yang verifikasi)
     *   + ditambah teknisi_id (FK ke admins role=teknisi, siapa yang mengerjakan)
     *   + ditambah tanggal_mulai_perbaikan & tanggal_selesai_perbaikan
     *   + ditambah catatan_teknisi
     *   + ditambah softDeletes untuk keperluan rekapan arsip
     *
     * Field foto tetap pakai path string (bukan BLOB) — lebih efisien untuk web.
     * Simpan file foto di storage/app/public/laporans/.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelapor
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('FK ke users — pelapor (mahasiswa/dosen)');

            // Identitas laporan
            $table->string('nomor_tiket')->unique()
                  ->comment('Format: SMTFAS-YYYYMMDD-XXXX');
            $table->string('nama_barang', 100);
            $table->enum('jenis_barang', ['elektronik', 'non-elektronik']);

            // Lokasi
            $table->string('tempat_utama')
                  ->comment('Gedung utama, misal: Gedung A, Lab Komputer, Perpustakaan');
            $table->string('sub_lokasi')->nullable()
                  ->comment('Sub-lokasi spesifik, misal: Ruang 301');
            $table->string('lantai_kelas')->nullable();
            $table->string('tempat_label')
                  ->comment('Label lengkap lokasi untuk tampilan');

            // Detail kerusakan
            $table->text('deskripsi');
            $table->enum('tingkat_kerusakan', ['rendah', 'sedang', 'tinggi', 'kritis'])
                  ->nullable()
                  ->comment('Ditentukan oleh AI berdasarkan foto + deskripsi');
            $table->unsignedTinyInteger('prioritas_ai')->nullable()
                  ->comment('Skor prioritas AI: 1–100. Makin tinggi makin mendesak');

            // Foto (path ke storage, bukan BLOB — lebih efisien)
            $table->string('foto_atas')->nullable();
            $table->string('foto_bawah')->nullable();
            $table->string('foto_kanan')->nullable();
            $table->string('foto_kiri')->nullable();
            $table->string('foto_depan')->nullable();
            $table->string('foto_belakang')->nullable();

            // Status & alur kerja
            $table->enum('status', ['menunggu', 'diverifikasi', 'diproses', 'selesai', 'ditolak'])
                  ->default('menunggu');
            $table->text('catatan_admin')->nullable()
                  ->comment('Catatan dari stakeholder saat verifikasi/penolakan');
            $table->text('catatan_teknisi')->nullable()
                  ->comment('Catatan dari teknisi saat proses perbaikan');

            // Penugasan
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null')
                  ->comment('FK ke admins — stakeholder yang memverifikasi');
            $table->foreignId('teknisi_id')->nullable()->constrained('admins')->onDelete('set null')
                  ->comment('FK ke admins (role=teknisi) — teknisi yang menangani');

            // Timeline perbaikan
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_mulai_perbaikan')->nullable();
            $table->timestamp('tanggal_selesai_perbaikan')->nullable();

            $table->timestamps();
            $table->softDeletes()
                  ->comment('Data tidak benar-benar dihapus, diarsipkan ke tabel rekapans');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
