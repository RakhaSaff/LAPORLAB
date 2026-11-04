<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_barang',
        'kode_barang',
        'foto_kode',
        'jenis_barang',
        'tempat_utama',
        'sub_lokasi',
        'lantai_kelas',
        'tempat_label',
        'kegiatan_akademik',
        'deskripsi',
        'foto_atas',
        'foto_bawah',
        'foto_kanan',
        'foto_kiri',
        'foto_depan',
        'foto_belakang',
        'status',
        'nomor_tiket',
        'catatan_admin',
        'total_skor',
        'prioritas',
        'tingkat_kerusakan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // RELASI
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name'  => 'User Dihapus',
            'email' => '-',
        ]);
    }

    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class);
    }

    // STATUS LABEL
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'menunggu'     => 'Menunggu',
            'diverifikasi' => 'Diverifikasi',
            'diproses'     => 'Sedang Diproses',
            'selesai'      => 'Selesai',
            'ditolak'      => 'Ditolak',
            default        => 'Tidak Diketahui',
        };
    }

    // LABEL TINGKAT KERUSAKAN (8 opsi baru + fallback data lama)
    public function getTingkatKerusakanLabelAttribute()
    {
        return match($this->tingkat_kerusakan) {
            'baik'               => 'Baik',
            'perlu_pemeliharaan' => 'Perlu Pemeliharaan',
            'rusak_ringan'       => 'Rusak Ringan',
            'rusak_sedang'       => 'Rusak Sedang',
            'rusak_berat'        => 'Rusak Berat',
            'rusak_total'        => 'Rusak Total',
            'tidak_layak_pakai'  => 'Tidak Layak Pakai',
            'hilang'             => 'Hilang',
            'rendah'             => 'Rendah',
            'sedang'             => 'Sedang',
            'tinggi'             => 'Tinggi',
            default              => $this->tingkat_kerusakan ? ucfirst($this->tingkat_kerusakan) : '-',
        };
    }

    // WARNA BADGE UNTUK TINGKAT KERUSAKAN
    public function getTingkatKerusakanBadgeAttribute()
    {
        $berat  = ['rusak_berat', 'rusak_total', 'tidak_layak_pakai', 'hilang', 'tinggi'];
        $sedang = ['rusak_sedang', 'sedang'];

        if (in_array($this->tingkat_kerusakan, $berat)) return 'badge-tinggi';
        if (in_array($this->tingkat_kerusakan, $sedang)) return 'badge-sedang';
        return 'badge-rendah';
    }

    // INFO BARANG LENGKAP (buat autofill Manajemen Perbaikan)
    public function getInfoBarangLengkapAttribute(): string
    {
        $kode   = $this->kode_barang ? "Kode: {$this->kode_barang}" : "Kode: Belum tercatat";
        $lokasi = $this->tempat_label ?: ($this->tempat_utama ?? '-');

        return "{$this->nama_barang} — {$kode} — {$lokasi}";
    }

    // FOTO
    public function getFotoUrlsAttribute()
    {
        $sisi = ['atas', 'bawah', 'kanan', 'kiri', 'depan', 'belakang'];
        $urls = [];
        foreach ($sisi as $s) {
            $field    = 'foto_' . $s;
            $urls[$s] = $this->$field ? asset('storage/' . $this->$field) : null;
        }
        return $urls;
    }

    // 🔥 RANKING SYSTEM FINAL 🔥

    // Bobot lokasi: sekarang seluruh pilihan tempat adalah laboratorium
    // (44 lab sesuai "Pemetaan Laboratorium FST UINSA"), jadi semua diberi
    // bobot yang sama seperti bobot laboratorium sebelumnya (40).
    private static array $bobotLokasi = [
        'lab_fakultas_fst_basic_biology_laboratory' => 40,
        'lab_fakultas_fst_basic_chemistry_laboratory' => 40,
        'lab_fakultas_fst_instrumentation_laboratory' => 40,
        'lab_fakultas_fst_laboratorium_green_house_i' => 40,
        'lab_fakultas_fst_laboratorium_green_house_ii' => 40,
        'lab_biologi_animal_cell_and_tissue_culture_laboratory' => 40,
        'lab_biologi_microbiology_laboratory' => 40,
        'lab_biologi_plant_physiology_laboratory' => 40,
        'lab_biologi_animal_physiology_laboratory' => 40,
        'lab_biologi_plant_cell_and_tissue_culture_laboratory' => 40,
        'lab_biologi_biosystematics_laboratory' => 40,
        'lab_biologi_ecology_laboratory' => 40,
        'lab_matematika_intelligent_computation_and_vision_laboratory' => 40,
        'lab_matematika_falaq_science_laboratory' => 40,
        'lab_matematika_modelling_and_simulation_laboratory' => 40,
        'lab_ilmu_kelautan_marine_natural_product' => 40,
        'lab_ilmu_kelautan_marine_pollution_laboratory' => 40,
        'lab_ilmu_kelautan_microbiology_and_marine_biotechnology_laboratory' => 40,
        'lab_ilmu_kelautan_coastal_and_marine_spatial_laboratory' => 40,
        'lab_ilmu_kelautan_ocean_modelling_laboratory' => 40,
        'lab_ilmu_kelautan_oceanographic_and_meteorological_laboratory' => 40,
        'lab_ilmu_kelautan_coastal_community_development_laboratory' => 40,
        'lab_ilmu_kelautan_marine_environmental_laboratory' => 40,
        'lab_teknik_sipil_hydrology_hydraulics_laboratory' => 40,
        'lab_teknik_sipil_laboratorium_concrete_dan_material' => 40,
        'lab_teknik_sipil_structure_laboratory' => 40,
        'lab_teknik_sipil_transportation_laboratory' => 40,
        'lab_teknik_sipil_civil_engineering_computer_laboratory' => 40,
        'lab_teknik_lingkungan_air_quality_and_noise_laboratory' => 40,
        'lab_teknik_lingkungan_environmental_chemistry_laboratory' => 40,
        'lab_teknik_lingkungan_environmental_microbiology_and_biotechnology' => 40,
        'lab_teknik_lingkungan_mapping_and_modelling_laboratory' => 40,
        'lab_teknik_lingkungan_sanitation_and_remediation_laboratory' => 40,
        'lab_teknik_lingkungan_water_quality_laboratory' => 40,
        'lab_arsitektur_teknik_komputer_arsitektur' => 40,
        'lab_arsitektur_laboratorium_ilmu_bangunan_dan_teknologi_arsitektur' => 40,
        'lab_arsitektur_laboratorium_studio_desain' => 40,
        'lab_arsitektur_laboratorium_desain_dan_bahan_bangunan' => 40,
        'lab_arsitektur_laboratorium_fotografi_arsitektur' => 40,
        'lab_sistem_informasi_database_laboratory' => 40,
        'lab_sistem_informasi_it_governance_laboratory' => 40,
        'lab_sistem_informasi_intelligent_system_laboratory' => 40,
        'lab_sistem_informasi_os_networking_laboratory' => 40,
        'lab_sistem_informasi_programming_laboratory' => 40,

        // fallback untuk data laporan lama (sebelum lokasi diganti ke 44 lab baru),
        // biar riwayat laporan lama tetap terhitung skornya secara wajar
        'lab_si' => 40, 'lab_arsi' => 40, 'lab_kelautan' => 40,
        'lab_sipil' => 40, 'lab_bio' => 40, 'lab_matematika' => 40, 'lab_tl' => 40,
        'ruang_kelas_6' => 35, 'ruang_kelas_7' => 35,
        'ruang_kelas_8' => 35, 'ruang_kelas_9' => 35,
        'ruang_ta' => 30, 'akademik' => 30,
        'prodi_si' => 25, 'prodi_arsi' => 25, 'prodi_bio' => 25,
        'prodi_mat' => 25, 'prodi_sipil' => 25,
        'prodi_tl' => 25, 'prodi_kelautan' => 25,
        'podcast' => 15,
        'toilet_lk' => 10, 'toilet_pr' => 10,
        'musholla_7' => 8, 'musholla_8' => 8, 'musholla_9' => 8,
    ];

    private static array $bobotTingkatKerusakan = [
        'baik'               => 0,
        'perlu_pemeliharaan' => 10,
        'rusak_ringan'       => 20,
        'rusak_sedang'       => 40,
        'rusak_berat'        => 60,
        'rusak_total'        => 80,
        'tidak_layak_pakai'  => 90,
        'hilang'             => 100,
        // fallback data lama, biar skor tetap konsisten kalau ada yang masih pakai value lama
        'rendah'             => 15,
        'sedang'             => 45,
        'tinggi'             => 75,
    ];

    public function hitungSkor(): int
    {
        $skor = 0;

        // 1. Lokasi
        $lokasi = strtolower(trim($this->tempat_utama ?? ''));
        $skor += self::$bobotLokasi[$lokasi] ?? 10;

        // 2. Jenis Barang
        if ($this->jenis_barang === 'elektronik') {
            $skor += 30;
        } else {
            $skor += 10;
        }

        // 3. Kegiatan Akademik
        $kegiatan = strtolower(trim($this->kegiatan_akademik ?? ''));

        if ($kegiatan === 'ya') {
            $skor += 30;
        } elseif ($kegiatan === 'tidak') {
            $skor += 10;
        } else {
            $skor += 5;
        }

        // 4. Tingkat Kerusakan (input user)
        $tingkat = strtolower(trim($this->tingkat_kerusakan ?? ''));
        $skor += self::$bobotTingkatKerusakan[$tingkat] ?? 0;

        return min(100, $skor);
    }

    public function getPrioritasLabel(): string
    {
        $skor = $this->total_skor > 0 ? $this->total_skor : $this->hitungSkor();

        if ($skor >= 70) return 'tinggi';
        if ($skor >= 40) return 'sedang';
        return 'rendah';
    }
}