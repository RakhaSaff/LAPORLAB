<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_asets', function (Blueprint $table) {
            $table->id();

            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');

            $table->enum('jenis_aset', ['barang_tetap','barang_tidak_tetap']);

            $table->string('kategori')->nullable();
            $table->string('lokasi')->nullable();

            $table->year('tahun_perolehan')->nullable();

            $table->text('spesifikasi')->nullable();

            $table->integer('jumlah_kerusakan')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_asets');
    }
};