<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelAset extends Model
{
    protected $table = 'label_asets';

    protected $fillable = [
        'foto_path',
        'jenis_barang',
        'kode_barang',
        'tahun',
        'lokasi',
        'jumlah',
        'nomor_urut_awal',
        'nomor_urut_akhir',
        'labels',
        'generated_by',
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'generated_by');
    }
}
