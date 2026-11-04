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
        Schema::table('master_asets', function (Blueprint $table) {
            // Kolom baru sesuai format tabel aset (Nama Alat, Merk, No. Inventarisasi, Jumlah, Keadaan, Keterangan)
            $table->string('merk', 150)->nullable()->after('nama_barang');
            $table->unsignedInteger('jumlah')->nullable()->after('kode_barang');
            $table->string('keadaan', 100)->nullable()->after('jumlah');
            $table->text('keterangan')->nullable()->after('keadaan');

            // Kolom lama yang sudah tidak dipakai di tampilan Master Data
            $table->dropColumn(['spesifikasi', 'lokasi', 'tahun_perolehan']);
        });

        // jenis_aset tidak lagi wajib diisi
        Schema::table('master_asets', function (Blueprint $table) {
            $table->string('jenis_aset', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_asets', function (Blueprint $table) {
            $table->dropColumn(['merk', 'jumlah', 'keadaan', 'keterangan']);

            $table->text('spesifikasi')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->integer('tahun_perolehan')->nullable();

            $table->string('jenis_aset', 50)->nullable(false)->change();
        });
    }
};