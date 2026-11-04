<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAset extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'jenis_aset',
        'kategori',
        'lokasi',
        'tahun_perolehan',
        'spesifikasi',
        'jumlah_kerusakan',
    ];

    public function getJenisAsetLabelAttribute(): string
    {
        return match ($this->jenis_aset) {
            'barang_tetap'       => 'Barang Tetap',
            'barang_tidak_tetap' => 'Barang Tidak Tetap',
            default              => '-',
        };
    }
}