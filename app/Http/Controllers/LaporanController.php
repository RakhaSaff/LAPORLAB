<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\MasterAset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        // Barang elektronik dari Master Data/dropdown wajib punya kode barang.
        // Foto kode barang OPSIONAL (membantu admin memvalidasi, tapi tidak wajib).
        // Kalau pelapor memakai "isi manual" (barang belum punya kode / belum ada di Master Data),
        // kode barang juga opsional.
        $wajibKode = !$request->boolean('input_manual') && $request->jenis_barang === 'elektronik';

        $request->validate([
            'nama_barang'       => 'required|string|max:150',
            'kode_barang'       => [Rule::requiredIf($wajibKode), 'nullable', 'string', 'max:50'],
            'jenis_barang'      => 'required|in:elektronik,non-elektronik',
            'tempat_utama'      => 'required|string',
            'deskripsi'         => 'required|string|max:250',
            'tingkat_kerusakan' => 'required|in:perlu_pemeliharaan,rusak_ringan,rusak_sedang,rusak_berat,rusak_total,tidak_layak_pakai,hilang',
            'foto_kode'         => 'nullable|image|max:5120',
            'foto_depan'        => 'required_without:base64_foto_depan|nullable|image|max:5120',
            // Semua pilihan tempat sekarang adalah laboratorium, jadi kegiatan akademik selalu wajib diisi.
            'kegiatan_akademik' => 'required|in:ya,tidak',
        ]);

        // Peta kode lokasi -> label lokasi yang tersimpan di laporan.
        // Sesuai "Pemetaan Laboratorium FST UINSA" (44 laboratorium, per Kelompok/Prodi).
        $lokasiMap = [
            'lab_fakultas_fst_basic_biology_laboratory' => 'Basic Biology Laboratory (Gedung Terpadu FST Lantai 5)',
            'lab_fakultas_fst_basic_chemistry_laboratory' => 'Basic Chemistry Laboratory (Gedung Terpadu FST Lantai 5)',
            'lab_fakultas_fst_instrumentation_laboratory' => 'Instrumentation Laboratory (Gedung Terpadu FST Lantai 4)',
            'lab_fakultas_fst_laboratorium_green_house_i' => 'Laboratorium Green House I (Gedung Laboratorium Saintek Rooftop)',
            'lab_fakultas_fst_laboratorium_green_house_ii' => 'Laboratorium Green House II (Gedung Laboratorium Saintek Rooftop)',
            'lab_biologi_animal_cell_and_tissue_culture_laboratory' => 'Animal Cell and Tissue Culture Laboratory (Gedung Terpadu FST Lantai 4)',
            'lab_biologi_microbiology_laboratory' => 'Microbiology Laboratory (Gedung Terpadu FST Lantai 4)',
            'lab_biologi_plant_physiology_laboratory' => 'Plant Physiology Laboratory (Gedung Terpadu FST Lantai 4)',
            'lab_biologi_animal_physiology_laboratory' => 'Animal Physiology Laboratory (Gedung Terpadu FST Lantai 4)',
            'lab_biologi_plant_cell_and_tissue_culture_laboratory' => 'Plant Cell and Tissue Culture Laboratory (Gedung Terpadu FST Lantai 4)',
            'lab_biologi_biosystematics_laboratory' => 'Biosystematics Laboratory (Gedung Terpadu FST Lantai 5)',
            'lab_biologi_ecology_laboratory' => 'Ecology Laboratory (Gedung Terpadu FST Lantai 5)',
            'lab_matematika_intelligent_computation_and_vision_laboratory' => 'Intelligent Computation and Vision Laboratory (Gedung Terpadu FST Lantai 3)',
            'lab_matematika_falaq_science_laboratory' => 'Falaq Science Laboratory (Gedung Terpadu FST Lantai 3)',
            'lab_matematika_modelling_and_simulation_laboratory' => 'Modelling and Simulation Laboratory (Gedung Terpadu FST Lantai 3)',
            'lab_ilmu_kelautan_marine_natural_product' => 'Marine Natural Product (Gedung Terpadu FST Lantai 5)',
            'lab_ilmu_kelautan_marine_pollution_laboratory' => 'Marine Pollution Laboratory (Gedung Terpadu FST Lantai 6)',
            'lab_ilmu_kelautan_microbiology_and_marine_biotechnology_laboratory' => 'Microbiology and Marine Biotechnology Laboratory (Gedung Terpadu FST Lantai 6)',
            'lab_ilmu_kelautan_coastal_and_marine_spatial_laboratory' => 'Coastal and Marine Spatial Laboratory (Gedung Terpadu FST Lantai 6)',
            'lab_ilmu_kelautan_ocean_modelling_laboratory' => 'Ocean Modelling Laboratory (Gedung Terpadu FST Lantai 6)',
            'lab_ilmu_kelautan_oceanographic_and_meteorological_laboratory' => 'Oceanographic and Meteorological Laboratory (Gedung Terpadu FST Lantai 6)',
            'lab_ilmu_kelautan_coastal_community_development_laboratory' => 'Coastal Community Development Laboratory (Gedung Terpadu FST Lantai 6)',
            'lab_ilmu_kelautan_marine_environmental_laboratory' => 'Marine Environmental Laboratory (Gedung Laboratorium Saintek Lantai 1)',
            'lab_teknik_sipil_hydrology_hydraulics_laboratory' => 'Hydrology & Hydraulics Laboratory (Gedung Laboratorium Saintek Lantai 1)',
            'lab_teknik_sipil_laboratorium_concrete_dan_material' => 'Laboratorium Concrete dan Material (Gedung Laboratorium Saintek Lantai 1)',
            'lab_teknik_sipil_structure_laboratory' => 'Structure Laboratory (Gedung Laboratorium Saintek Lantai 1)',
            'lab_teknik_sipil_transportation_laboratory' => 'Transportation Laboratory (Gedung Laboratorium Saintek Lantai 1)',
            'lab_teknik_sipil_civil_engineering_computer_laboratory' => 'Civil Engineering Computer Laboratory (Gedung Laboratorium Saintek Lantai 3)',
            'lab_teknik_lingkungan_air_quality_and_noise_laboratory' => 'Air Quality and Noise Laboratory (Gedung Laboratorium Saintek Lantai 4)',
            'lab_teknik_lingkungan_environmental_chemistry_laboratory' => 'Environmental Chemistry Laboratory (Gedung Laboratorium Saintek Lantai 4)',
            'lab_teknik_lingkungan_environmental_microbiology_and_biotechnology' => 'Environmental Microbiology and Biotechnology (Gedung Laboratorium Saintek Lantai 2)',
            'lab_teknik_lingkungan_mapping_and_modelling_laboratory' => 'Mapping and Modelling Laboratory (Gedung Laboratorium Saintek Lantai 3)',
            'lab_teknik_lingkungan_sanitation_and_remediation_laboratory' => 'Sanitation and Remediation Laboratory (Gedung Laboratorium Saintek Lantai 2)',
            'lab_teknik_lingkungan_water_quality_laboratory' => 'Water Quality Laboratory (Gedung Laboratorium Saintek Lantai 3)',
            'lab_arsitektur_teknik_komputer_arsitektur' => 'Teknik Komputer Arsitektur (Gedung Terpadu FST Lantai 8)',
            'lab_arsitektur_laboratorium_ilmu_bangunan_dan_teknologi_arsitektur' => 'Laboratorium Ilmu Bangunan dan Teknologi Arsitektur (Gedung Terpadu FST Lantai 8)',
            'lab_arsitektur_laboratorium_studio_desain' => 'Laboratorium Studio Desain (Gedung Terpadu FST Lantai 8)',
            'lab_arsitektur_laboratorium_desain_dan_bahan_bangunan' => 'Laboratorium Desain dan Bahan Bangunan (Gedung Terpadu FST Lantai 9)',
            'lab_arsitektur_laboratorium_fotografi_arsitektur' => 'Laboratorium Fotografi Arsitektur (Gedung Terpadu FST Lantai 9)',
            'lab_sistem_informasi_database_laboratory' => 'Database Laboratory (Gedung Terpadu FST Lantai 7)',
            'lab_sistem_informasi_it_governance_laboratory' => 'IT Governance Laboratory (Gedung Terpadu FST Lantai 7)',
            'lab_sistem_informasi_intelligent_system_laboratory' => 'Intelligent System Laboratory (Gedung Terpadu FST Lantai 7)',
            'lab_sistem_informasi_os_networking_laboratory' => 'OS & Networking Laboratory (Gedung Terpadu FST Lantai 7)',
            'lab_sistem_informasi_programming_laboratory' => 'Programming Laboratory (Gedung Terpadu FST Lantai 7)',
        ];

        $tempatLabel = $lokasiMap[$request->tempat_utama]
                       ?? ucwords(str_replace('_', ' ', $request->tempat_utama));

        $sisi  = ['depan']; // satu foto kerusakan, disimpan di kolom foto_depan
        $fotos = [];
        foreach ($sisi as $s) {
            $field  = 'foto_' . $s;
            $base64 = 'base64_foto_' . $s;

            if ($request->hasFile($field)) {
                $fotos[$field] = $request->file($field)->store('laporans', 'public');
            } elseif ($request->filled($base64)) {
                $data     = $request->input($base64);
                $imgData  = substr($data, strpos($data, ',') + 1);
                $imgBin   = base64_decode($imgData);
                $filename = 'laporans/' . Str::uuid() . '.jpg';
                Storage::disk('public')->put($filename, $imgBin);
                $fotos[$field] = $filename;
            } else {
                $fotos[$field] = null;
            }
        }

        // Foto kode barang (opsional): boleh upload file, ambil dari kamera, atau dikosongkan
        $fotoKodePath = null;
        if ($request->hasFile('foto_kode')) {
            $fotoKodePath = $request->file('foto_kode')->store('laporans/kode', 'public');
        } elseif ($request->filled('base64_foto_kode')) {
            $data     = $request->input('base64_foto_kode');
            $imgData  = substr($data, strpos($data, ',') + 1);
            $imgBin   = base64_decode($imgData);
            $filename = 'laporans/kode/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($filename, $imgBin);
            $fotoKodePath = $filename;
        }

        $nomorTiket = 'SMTFAS-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        $laporan = Laporan::create([
            'user_id'           => Auth::id(),
            'nomor_tiket'       => $nomorTiket,
            'nama_barang'       => $request->nama_barang,
            'kode_barang'       => $request->kode_barang,
            'foto_kode'         => $fotoKodePath,
            'jenis_barang'      => $request->jenis_barang,
            'tempat_utama'      => $request->tempat_utama,
            'tempat_label'      => $tempatLabel,
            'kegiatan_akademik' => $request->kegiatan_akademik,
            'deskripsi'         => $request->deskripsi,
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
            'status'            => 'menunggu',
            'total_skor'        => 0,
            'prioritas'         => 'rendah',
            ...$fotos,
        ]);

        $skor = $laporan->hitungSkor();

        $laporan->total_skor = $skor;
        $laporan->prioritas  = $laporan->getPrioritasLabel();
        $laporan->save();

        return redirect()->route('laporan.success', $laporan->id);
    }

    // Cari nama alat di Master Data berdasarkan No. Inventarisasi / kode barang
    public function cariKode(Request $request)
    {
        $kode = trim((string) $request->query('kode', ''));
        if ($kode === '') {
            return response()->json(['items' => []]);
        }

        $rows = MasterAset::whereRaw('LOWER(TRIM(kode_barang)) = ?', [mb_strtolower($kode)])
            ->get(['nama_barang', 'merk']);

        // Kode yang sama dipakai di banyak ruangan, jadi nama yang cuma beda
        // spasi/titik/huruf besar (mis. "A.C. Split" dan "A.C.Split") digabung jadi satu pilihan.
        $items = $rows
            ->groupBy(fn ($r) => preg_replace('/[^a-z0-9]/', '', mb_strtolower($r->nama_barang)))
            ->map(function ($group) {
                $nama = $group->groupBy('nama_barang')
                              ->sortByDesc(fn ($g) => $g->count())
                              ->keys()->first();
                $merk = $group->pluck('merk')->filter()->countBy()
                              ->sortDesc()->keys()->first();
                return ['nama_barang' => $nama, 'merk' => $merk];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    public function success($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporan.success', compact('laporan'));
    }

    public function laporanSaya()
    {
        $laporans = Laporan::where('user_id', Auth::id())
                          ->orderByDesc('created_at')
                          ->paginate(10);
        return view('laporan.laporan-saya', compact('laporans'));
    }

    public function index()
    {
        $laporan = Laporan::with('user')->orderByDesc('created_at')->paginate(15);
        return view('laporan.index', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = Laporan::where('user_id', Auth::id())->findOrFail($id);
        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::where('user_id', Auth::id())->findOrFail($id);
        $request->validate(['deskripsi' => 'required|string|max:250']);
        $laporan->update(['deskripsi' => $request->deskripsi]);
        return back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = Laporan::where('user_id', Auth::id())->findOrFail($id);
        $laporan->delete();
        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}