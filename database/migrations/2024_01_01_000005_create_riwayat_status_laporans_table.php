<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel riwayat_status_laporans — audit trail perubahan status laporan.
     * Setiap perubahan status laporan dicatat di sini.
     *
     * Berguna untuk:
     *   - Halaman "Manajemen Perbaikan" (stakeholder melihat kronologi)
     *   - Halaman "Laporan Saya" (pelapor melihat histori update)
     *   - Bahan laporan pimpinan (rekap kecepatan penanganan)
     */
    public function up(): void
    {
        Schema::create('riwayat_status_laporans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade')
                  ->comment('FK ke laporans');

            $table->enum('status_lama', ['menunggu', 'diverifikasi', 'diproses', 'selesai', 'ditolak'])
                  ->nullable();
            $table->enum('status_baru', ['menunggu', 'diverifikasi', 'diproses', 'selesai', 'ditolak']);

            $table->text('keterangan')->nullable()
                  ->comment('Alasan perubahan status atau catatan');

            // Siapa yang mengubah status
            $table->foreignId('diubah_oleh_admin')->nullable()->constrained('admins')->onDelete('set null')
                  ->comment('FK ke admins jika stakeholder/teknisi yang mengubah');
            $table->foreignId('diubah_oleh_user')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('FK ke users (jarang, umumnya cancel oleh pelapor)');

            $table->timestamp('changed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_laporans');
    }
};
