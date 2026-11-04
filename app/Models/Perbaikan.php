<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'nama_teknisi',
        'tanggal_perbaikan',
        'barang_diperbaiki',
        'kode_inv',
        'keterangan',
        'status_perbaikan',
        'bukti_foto',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}