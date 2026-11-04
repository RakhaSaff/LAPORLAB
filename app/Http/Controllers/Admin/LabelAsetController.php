<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabelAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabelAsetController extends Controller
{
    // Daftar jenis barang + kode singkatan
    public static function daftarBarang(): array
    {
        return [
            'PC / Komputer'   => 'PC',
            'Laptop'          => 'LPT',
            'Proyektor'       => 'PROJ',
            'Kursi'           => 'KRS',
            'Meja'            => 'MJA',
            'AC'              => 'AC',
            'Whiteboard'      => 'WB',
            'UPS'             => 'UPS',
            'Smart TV'        => 'TV',
            'Printer'         => 'PRN',
            'Scanner'         => 'SCN',
            'Router/Switch'   => 'NET',
            'Kamera CCTV'     => 'CCTV',
            'Lemari'          => 'LMR',
            'Rak'             => 'RAK',
            'Kipas Angin'     => 'FAN',
            'Lampu'           => 'LMP',
            'Kabel LAN'       => 'LAN',
            'Server'          => 'SRV',
            'Lainnya'         => 'LNY',
        ];
    }

    public function index()
    {
        $daftarBarang = self::daftarBarang();
        $riwayat = LabelAset::latest()->take(10)->get();
        return view('admin.label-aset.index', compact('daftarBarang', 'riwayat'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'foto'        => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'jenis_barang'=> 'required|string',
            'jumlah'      => 'required|integer|min:1|max:500',
            'lokasi'      => 'nullable|string|max:100',
        ], [
            'foto.required'        => 'Foto barang wajib diupload.',
            'foto.image'           => 'File harus berupa gambar.',
            'jenis_barang.required'=> 'Pilih jenis barang terlebih dahulu.',
            'jumlah.required'      => 'Jumlah barang wajib diisi.',
            'jumlah.min'           => 'Jumlah minimal 1.',
            'jumlah.max'           => 'Jumlah maksimal 500.',
        ]);

        $daftarBarang = self::daftarBarang();
        $kodeBarang   = $daftarBarang[$request->jenis_barang] ?? 'LNY';
        $tahun        = date('Y');
        $jumlah       = (int) $request->jumlah;

        // Simpan foto
        $fotoPath = $request->file('foto')->store('label-aset/foto', 'public');

        // Cari nomor urut terakhir untuk jenis barang ini di tahun ini
        $lastLabel = LabelAset::where('jenis_barang', $request->jenis_barang)
            ->where('tahun', $tahun)
            ->orderBy('nomor_urut_akhir', 'desc')
            ->first();

        $startNumber = $lastLabel ? $lastLabel->nomor_urut_akhir + 1 : 1;

        // Generate label
        $labels = [];
        for ($i = 0; $i < $jumlah; $i++) {
            $nomor    = str_pad($startNumber + $i, 3, '0', STR_PAD_LEFT);
            $labels[] = "SMTFAS-{$kodeBarang}-{$tahun}-{$nomor}";
        }

        // Simpan ke database
        $labelAset = LabelAset::create([
            'foto_path'      => $fotoPath,
            'jenis_barang'   => $request->jenis_barang,
            'kode_barang'    => $kodeBarang,
            'tahun'          => $tahun,
            'lokasi'         => $request->lokasi,
            'jumlah'         => $jumlah,
            'nomor_urut_awal'=> $startNumber,
            'nomor_urut_akhir'=> $startNumber + $jumlah - 1,
            'labels'         => json_encode($labels),
            'generated_by'   => Auth::guard('admin')->id(),
        ]);

        return view('admin.label-aset.result', compact('labels', 'labelAset', 'fotoPath'));
    }

    public function riwayat()
    {
        $riwayat = LabelAset::with('admin')->latest()->paginate(15);
        return view('admin.label-aset.riwayat', compact('riwayat'));
    }

    public function detail($id)
    {
        $labelAset = LabelAset::findOrFail($id);
        $labels    = json_decode($labelAset->labels, true);
        $fotoPath  = $labelAset->foto_path;
        return view('admin.label-aset.result', compact('labels', 'labelAset', 'fotoPath'));
    }
}
