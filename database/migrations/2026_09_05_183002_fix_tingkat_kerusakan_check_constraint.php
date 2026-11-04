<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE laporans DROP CONSTRAINT IF EXISTS laporans_tingkat_kerusakan_check');

        DB::statement("ALTER TABLE laporans ADD CONSTRAINT laporans_tingkat_kerusakan_check 
            CHECK (tingkat_kerusakan IS NULL OR tingkat_kerusakan IN (
                'rendah','sedang','tinggi',
                'baik','perlu_pemeliharaan','rusak_ringan','rusak_sedang','rusak_berat','rusak_total','tidak_layak_pakai','hilang'
            ))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE laporans DROP CONSTRAINT IF EXISTS laporans_tingkat_kerusakan_check');

        DB::statement("ALTER TABLE laporans ADD CONSTRAINT laporans_tingkat_kerusakan_check 
            CHECK (tingkat_kerusakan IN ('rendah','sedang','tinggi'))");
    }
};