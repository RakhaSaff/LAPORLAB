<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel admins — stakeholder/pengelola Fakultas Saintek.
     * Bertugas memverifikasi laporan, menentukan prioritas, dan menugaskan teknisi.
     *
     * Disempurnakan dari file asli Anda:
     *   + ditambah role untuk membedakan level akses (stakeholder vs admin)
     *   + ditambah foto_profil
     *   + ditambah softDeletes
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('nip')->unique()
                  ->comment('Nomor Induk Pegawai');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('jabatan')
                  ->comment('Contoh: Kepala Laboratorium, Staff TU, Dekan, Teknisi');
            $table->enum('role', ['superadmin', 'stakeholder', 'teknisi'])
                  ->default('stakeholder')
                  ->comment('superadmin=akses penuh, stakeholder=verifikasi+assign, teknisi=perbaikan');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('no_hp', 20)->nullable();
            $table->string('foto_profil')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
