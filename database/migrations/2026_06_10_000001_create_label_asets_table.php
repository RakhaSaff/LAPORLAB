<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('label_asets', function (Blueprint $table) {
            $table->id();
            $table->string('foto_path');
            $table->string('jenis_barang');
            $table->string('kode_barang', 10);
            $table->string('tahun', 4);
            $table->string('lokasi')->nullable();
            $table->integer('jumlah');
            $table->integer('nomor_urut_awal');
            $table->integer('nomor_urut_akhir');
            $table->longText('labels');
            $table->foreignId('generated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('label_asets');
    }
};