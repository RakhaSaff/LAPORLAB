<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportLaporanService
{
    private array $columnMap = [
        // nama_barang
        'nama_barang'      => 'nama_barang',
        'nama barang'      => 'nama_barang',
        'barang'           => 'nama_barang',
        'nama'             => 'nama_barang',
        'item'             => 'nama_barang',
        'asset'            => 'nama_barang',
        'aset'             => 'nama_barang',
        'nama_aset'        => 'nama_barang',
        'nama aset'        => 'nama_barang',
        'nama_fasilitas'   => 'nama_barang',
        'nama fasilitas'   => 'nama_barang',
        'fasilitas'        => 'nama_barang',

        // kode_barang — kolom kode/nomor identifikasi barang, apapun nama kolomnya di file sumber
        'kode_barang'      => 'kode_barang',
        'kode barang'      => 'kode_barang',
        'id_barang'        => 'kode_barang',
        'id barang'        => 'kode_barang',
        'kode_aset'        => 'kode_barang',
        'kode aset'        => 'kode_barang',
        'id_aset'          => 'kode_barang',
        'id aset'          => 'kode_barang',
        'kode_fasilitas'   => 'kode_barang',
        'kode fasilitas'   => 'kode_barang',
        'kode_inventaris'  => 'kode_barang',
        'kode inventaris'  => 'kode_barang',
        'no_inv'           => 'kode_barang',
        'no inv'           => 'kode_barang',
        'no.inv'           => 'kode_barang',
        'no_inventaris'    => 'kode_barang',
        'no inventaris'    => 'kode_barang',
        'nomor_inventaris' => 'kode_barang',
        'nomor inventaris' => 'kode_barang',
        'inventaris'       => 'kode_barang',
        'nomor_inventaris_barang' => 'kode_barang',
        'kode_item'        => 'kode_barang',
        'kode item'        => 'kode_barang',
        'item_code'        => 'kode_barang',
        'asset_code'       => 'kode_barang',
        'asset code'       => 'kode_barang',
        'inventory_code'   => 'kode_barang',
        'inventory code'   => 'kode_barang',
        'kode'             => 'kode_barang',
        'nomor_referensi'  => 'kode_barang',
        'nomor referensi'  => 'kode_barang',
        'no_referensi'     => 'kode_barang',
        'no referensi'     => 'kode_barang',
        'referensi'        => 'kode_barang',
        'reference'        => 'kode_barang',
        'reference_number' => 'kode_barang',
        'reference number' => 'kode_barang',
        'ref'              => 'kode_barang',
        'no_ref'           => 'kode_barang',
        'nomor_ref'        => 'kode_barang',

        // jenis_barang
        'jenis_barang'     => 'jenis_barang',
        'jenis barang'     => 'jenis_barang',
        'jenis'            => 'jenis_barang',
        'kategori'         => 'jenis_barang',
        'category'         => 'jenis_barang',
        'tipe'             => 'jenis_barang',
        'type'             => 'jenis_barang',

        // tempat_utama
        'tempat_utama'     => 'tempat_utama',
        'tempat utama'     => 'tempat_utama',
        'lokasi'           => 'tempat_utama',
        'location'         => 'tempat_utama',
        'tempat'           => 'tempat_utama',
        'ruangan'          => 'tempat_utama',
        'ruang'            => 'tempat_utama',
        'gedung'           => 'tempat_utama',
        'building'         => 'tempat_utama',
        'room'             => 'tempat_utama',

        // sub_lokasi
        'sub_lokasi'       => 'sub_lokasi',
        'sub lokasi'       => 'sub_lokasi',
        'detail_lokasi'    => 'sub_lokasi',
        'detail lokasi'    => 'sub_lokasi',
        'sub_room'         => 'sub_lokasi',

        // deskripsi
        'deskripsi'        => 'deskripsi',
        'description'      => 'deskripsi',
        'keterangan'       => 'deskripsi',
        'detail'           => 'deskripsi',
        'catatan'          => 'deskripsi',
        'notes'            => 'deskripsi',
        'keluhan'          => 'deskripsi',
        'kerusakan'        => 'deskripsi',
        'kondisi'          => 'deskripsi',
        'uraian'           => 'deskripsi',
        'uraian_kerusakan' => 'deskripsi',
        'uraian kerusakan' => 'deskripsi',

        // tingkat_kerusakan
        'tingkat_kerusakan'=> 'tingkat_kerusakan',
        'tingkat kerusakan'=> 'tingkat_kerusakan',
        'tingkat'          => 'tingkat_kerusakan',
        'level'            => 'tingkat_kerusakan',
        'severity'         => 'tingkat_kerusakan',
        'prioritas'        => 'tingkat_kerusakan',
        'priority'         => 'tingkat_kerusakan',
        'urgensi'          => 'tingkat_kerusakan',

        // kegiatan_akademik
        'kegiatan_akademik'=> 'kegiatan_akademik',
        'kegiatan akademik'=> 'kegiatan_akademik',
        'kegiatan'         => 'kegiatan_akademik',
        'academic'         => 'kegiatan_akademik',

        // status
        'status'           => 'status',
        'kondisi_laporan'  => 'status',
        'progress'         => 'status',
        'state'            => 'status',

        // pelapor
        'pelapor'          => '_pelapor',
        'reporter'         => '_pelapor',
        'nama_pelapor'     => '_pelapor',
        'nama pelapor'     => '_pelapor',
        'email_pelapor'    => '_email_pelapor',
        'email pelapor'    => '_email_pelapor',
        'email'            => '_email_pelapor',

        // nomor tiket (referensi saja)
        'nomor_tiket'      => '_nomor_tiket_ref',
        'nomor tiket'      => '_nomor_tiket_ref',
        'tiket'            => '_nomor_tiket_ref',
        'ticket'           => '_nomor_tiket_ref',
        'no_tiket'         => '_nomor_tiket_ref',
    ];

    private array $jenisMap = [
        'elektronik'     => 'elektronik',
        'electronic'     => 'elektronik',
        'elektrik'       => 'elektronik',
        'electric'       => 'elektronik',
        'listrik'        => 'elektronik',
        'e'              => 'elektronik',
        'non-elektronik' => 'non-elektronik',
        'non elektronik' => 'non-elektronik',
        'non_elektronik' => 'non-elektronik',
        'mebel'          => 'non-elektronik',
        'furniture'      => 'non-elektronik',
        'mekanik'        => 'non-elektronik',
        'ne'             => 'non-elektronik',
        'n'              => 'non-elektronik',
    ];

    private array $statusMap = [
        'menunggu'        => 'menunggu',
        'pending'         => 'menunggu',
        'baru'            => 'menunggu',
        'new'             => 'menunggu',
        'open'            => 'menunggu',
        'diverifikasi'    => 'diverifikasi',
        'verified'        => 'diverifikasi',
        'diproses'        => 'diproses',
        'proses'          => 'diproses',
        'in progress'     => 'diproses',
        'sedang diproses' => 'diproses',
        'selesai'         => 'selesai',
        'done'            => 'selesai',
        'completed'       => 'selesai',
        'ditolak'         => 'ditolak',
        'rejected'        => 'ditolak',
    ];

    private array $tingkatMap = [
        'rendah'  => 'rendah',
        'low'     => 'rendah',
        'minor'   => 'rendah',
        'ringan'  => 'rendah',
        'sedang'  => 'sedang',
        'medium'  => 'sedang',
        'moderate'=> 'sedang',
        'tinggi'  => 'tinggi',
        'high'    => 'tinggi',
        'major'   => 'tinggi',
        'berat'   => 'tinggi',
        'kritis'  => 'tinggi',
        'critical'=> 'tinggi',
    ];

    private array $lokasiMap = [
        'lab si'               => 'lab_si',
        'lab sistem informasi' => 'lab_si',
        'lab arsitektur'       => 'lab_arsi',
        'lab arsi'             => 'lab_arsi',
        'lab kelautan'         => 'lab_kelautan',
        'lab sipil'            => 'lab_sipil',
        'lab teknik sipil'     => 'lab_sipil',
        'lab biologi'          => 'lab_bio',
        'lab bio'              => 'lab_bio',
        'lab matematika'       => 'lab_matematika',
        'lab mat'              => 'lab_matematika',
        'lab tl'               => 'lab_tl',
        'lab teknik lingkungan'=> 'lab_tl',
        'ruang ta'             => 'ruang_ta',
        'ruang kelas lantai 6' => 'ruang_kelas_6',
        'lantai 6'             => 'ruang_kelas_6',
        'ruang kelas lantai 7' => 'ruang_kelas_7',
        'lantai 7'             => 'ruang_kelas_7',
        'ruang kelas lantai 8' => 'ruang_kelas_8',
        'lantai 8'             => 'ruang_kelas_8',
        'ruang kelas lantai 9' => 'ruang_kelas_9',
        'lantai 9'             => 'ruang_kelas_9',
        'akademik'             => 'akademik',
        'ruang fakultas'       => 'akademik',
        'podcast'              => 'podcast',
        'toilet laki'          => 'toilet_lk',
        'toilet lk'            => 'toilet_lk',
        'toilet perempuan'     => 'toilet_pr',
        'toilet pr'            => 'toilet_pr',
        'musholla lantai 7'    => 'musholla_7',
        'musholla lantai 8'    => 'musholla_8',
        'musholla lantai 9'    => 'musholla_9',
        'prodi si'             => 'prodi_si',
        'prodi arsitektur'     => 'prodi_arsi',
        'prodi biologi'        => 'prodi_bio',
        'prodi matematika'     => 'prodi_mat',
        'prodi sipil'          => 'prodi_sipil',
        'prodi tl'             => 'prodi_tl',
        'prodi kelautan'       => 'prodi_kelautan',
    ];

    // Bersihin header kolom dari karakter tak kasat mata yang sering ikut
    // kebawa pas file CSV disimpan dari Excel: BOM (byte-order-mark) di kolom
    // pertama, dan non-breaking space. Tanpa ini, header yang KELIHATAN sama
    // ("nama_barang") bisa gagal dikenali karena beda di karakter tersembunyi.
    private function cleanHeader($header): string
    {
        $h = (string) $header;
        $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);        // BOM (byte mentah, sebelum jadi UTF-8 string)
        $h = preg_replace('/^\x{FEFF}/u', '', $h);            // BOM (kalau sudah kebaca sebagai karakter unicode)
        $h = str_replace("\xC2\xA0", ' ', $h);                 // non-breaking space -> spasi biasa
        $h = preg_replace('/\s+/', ' ', $h);                   // rapikan spasi ganda
        return strtolower(trim($h));
    }

    // Fallback kalau nama kolom gak persis ada di whitelist $columnMap, tapi
    // jelas-jelas mengandung kata kunci yang khas buat kode/nomor identifikasi
    // barang di mana pun posisinya (bukan harus sama persis). Ini jaring pengaman
    // buat nama kolom dari sumber data lain yang belum kepikiran sebelumnya.
    private function guessColumnFallback(string $key): ?string
    {
        if (preg_match('/kode|inventaris|aset|asset/i', $key)) {
            return 'kode_barang';
        }
        // "inv" sebagai kata utuh, tapi anggap underscore/spasi juga sebagai pemisah kata
        // (bukan cuma karakter non-huruf), biar "inv_number" ikut kena, sementara
        // "investasi" tetap aman TIDAK ke-anggap kode barang.
        if (preg_match('/(?:^|[_\s])inv(?:$|[_\s])/i', $key)) {
            return 'kode_barang';
        }
        return null;
    }

    // ── Parse file ────────────────────────────────────────────────────────
    public function parse(string $filePath, string $extension): array
    {
        return match(strtolower($extension)) {
            'xlsx', 'xls' => $this->parseSpreadsheet($filePath),
            'csv'         => $this->parseCsv($filePath),
            default       => throw new \Exception("Format file .{$extension} tidak didukung. Gunakan Excel atau CSV."),
        };
    }

    // ── Import ke database (BULK INSERT) ────────────────────────────────────
    // Skor & prioritas dihitung di PHP (gak butuh query DB), semua baris
    // dikumpulkan dulu jadi array, lalu di-INSERT sekaligus per-chunk.
    // 729 baris = ±4 query, bukan ±1.458 query kayak versi create()+update() per baris.
    public function import(array $rows, int $importedBy): array
    {
        $errors   = [];
        $toInsert = [];
        $now      = now();

        // Pra-ambil user_id berdasarkan email pelapor (1 query, bukan per-baris)
        $emails = collect($rows)
            ->map(fn($r) => $this->mapRow($r)['_email_pelapor'] ?? null)
            ->filter()
            ->unique()
            ->values();

        $userMap = $emails->isNotEmpty()
            ? User::whereIn('email', $emails)->pluck('id', 'email')->toArray()
            : [];

        foreach ($rows as $index => $rawRow) {
            try {
                $mapped = $this->mapRow($rawRow);
                $this->validateRow($mapped, $index + 1);

                $userId = $importedBy;
                if (!empty($mapped['_email_pelapor']) && isset($userMap[$mapped['_email_pelapor']])) {
                    $userId = $userMap[$mapped['_email_pelapor']];
                }

                $tempatLabel = $mapped['tempat_label'] ?? ucwords(str_replace('_', ' ', $mapped['tempat_utama'] ?? 'Tidak Diketahui'));
                if (!empty($mapped['sub_lokasi'])) {
                    $tempatLabel .= ' - ' . ucwords(str_replace('_', ' ', $mapped['sub_lokasi']));
                }

                // Instance sementara (TIDAK disimpan) cuma buat pakai logika hitungSkor()
                // yang sudah ada di Model Laporan, biar rumusnya konsisten sama single-import.
                $temp = new Laporan([
                    'tempat_utama'      => $mapped['tempat_utama'],
                    'jenis_barang'      => $mapped['jenis_barang'] ?? 'non-elektronik',
                    'kegiatan_akademik' => $mapped['kegiatan_akademik'] ?? null,
                ]);
                $skor      = $temp->hitungSkor();
                $tingkat   = $mapped['tingkat_kerusakan'] ?? ($skor >= 70 ? 'tinggi' : ($skor >= 40 ? 'sedang' : 'rendah'));
                $prioritas = $skor >= 70 ? 'tinggi' : ($skor >= 40 ? 'sedang' : 'rendah');

                $toInsert[] = [
                    'user_id'           => $userId,
                    'nomor_tiket'       => 'SMTFAS-IMP-' . $now->format('Ymd') . '-' . strtoupper(Str::random(4)) . '-' . ($index + 1),
                    'nama_barang'       => $mapped['nama_barang'],
                    'kode_barang'       => $mapped['kode_barang'] ?? null,
                    'jenis_barang'      => $mapped['jenis_barang'] ?? 'non-elektronik',
                    'tempat_utama'      => $mapped['tempat_utama'],
                    'sub_lokasi'        => $mapped['sub_lokasi'] ?? null,
                    'tempat_label'      => $tempatLabel,
                    'kegiatan_akademik' => $mapped['kegiatan_akademik'] ?? null,
                    'deskripsi'         => $mapped['deskripsi'] ?? '-',
                    'status'            => $mapped['status'] ?? 'menunggu',
                    'tingkat_kerusakan' => $tingkat,
                    'total_skor'        => $skor,
                    'prioritas'         => $prioritas,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                Log::warning("ImportLaporan baris " . ($index + 1) . ": " . $e->getMessage());
            }
        }

        $berhasil = 0;

        DB::transaction(function () use ($toInsert, &$berhasil) {
            foreach (array_chunk($toInsert, 200) as $chunk) {
                Laporan::insert($chunk);
                $berhasil += count($chunk);
            }
        });

        $gagal = count($errors);

        return compact('berhasil', 'gagal', 'errors');
    }

    // ── Parser Excel ──────────────────────────────────────────────────────
    private function parseSpreadsheet(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $data        = $sheet->toArray(null, true, true, false);

        if (empty($data)) return [];

        $headers = array_map(fn($h) => $this->cleanHeader($h), $data[0]);
        $rows    = [];

        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];
            if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) continue;

            $assoc = [];
            foreach ($headers as $colIdx => $header) {
                $assoc[$header] = $row[$colIdx] ?? null;
            }
            $rows[] = $assoc;
        }

        return $rows;
    }

    // ── Parser CSV ────────────────────────────────────────────────────────
    private function parseCsv(string $filePath): array
    {
        $rows    = [];
        $headers = null;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($line = fgetcsv($handle, 0, ',')) !== false) {
                if (count($line) === 1) {
                    $line = str_getcsv($line[0], ';');
                }
                if ($headers === null) {
                    $headers = array_map(fn($h) => $this->cleanHeader($h), $line);
                    continue;
                }
                if (empty(array_filter($line))) continue;

                $assoc = [];
                foreach ($headers as $i => $header) {
                    $assoc[$header] = $line[$i] ?? null;
                }
                $rows[] = $assoc;
            }
            fclose($handle);
        }

        return $rows;
    }

    // ── Mapping & normalisasi ─────────────────────────────────────────────
    private function mapRow(array $rawRow): array
    {
        $mapped = [];

        foreach ($rawRow as $rawKey => $value) {
            $key      = $this->cleanHeader($rawKey);
            $smartKey = $this->columnMap[$key] ?? $this->guessColumnFallback($key);
            if ($smartKey) {
                $mapped[$smartKey] = $value !== '' ? $value : null;
            }
        }

        if (isset($mapped['jenis_barang'])) {
            $mapped['jenis_barang'] = $this->jenisMap[strtolower(trim($mapped['jenis_barang']))] ?? 'non-elektronik';
        }

        if (isset($mapped['status'])) {
            $mapped['status'] = $this->statusMap[strtolower(trim($mapped['status']))] ?? 'menunggu';
        }

        if (isset($mapped['tingkat_kerusakan'])) {
            $mapped['tingkat_kerusakan'] = $this->tingkatMap[strtolower(trim($mapped['tingkat_kerusakan']))] ?? null;
        }

        if (isset($mapped['tempat_utama'])) {
            $lokasiBersih           = strtolower(trim($mapped['tempat_utama']));
            $mapped['tempat_utama'] = $this->lokasiMap[$lokasiBersih] ?? $lokasiBersih;
            $mapped['tempat_label'] = ucwords(str_replace('_', ' ', $mapped['tempat_utama']));
        }

        // Ambil kode/nomor inventaris dari teks deskripsi HANYA kalau belum ada
        // kode_barang dari kolom terpisah (kolom terpisah lebih diprioritaskan,
        // regex ini cuma fallback buat file lama yang nyimpen kodenya nempel di deskripsi).
        if (empty($mapped['kode_barang']) && !empty($mapped['deskripsi'])) {
            $kode = $this->extractKodeBarang($mapped['deskripsi']);
            if ($kode) {
                $mapped['kode_barang'] = $kode;
            }
        }

        return $mapped;
    }

    // Cari kode/nomor inventaris yang nempel di teks deskripsi, dari berbagai label
    // yang mungkin dipakai sumber data beda-beda, misalnya:
    //   "Kondisi: Baik. No.Inv: 3.05.02.04.004. Merk: LG. Jumlah: 3 unit."
    //   "Kondisi: Baik. Kode: KODE-FAS-001. Merk: Krisbow. Jumlah: 1 unit."
    //   "Kondisi: Baik. Kode Aset: AST-002. Merk: ..."
    // Return null kalau gak ada pola yang cocok sama sekali (deskripsi bebas tanpa kode).
    private function extractKodeBarang(string $deskripsi): ?string
    {
        $labelPattern = '(?:No\.?\s*Inv(?:entaris)?|Nomor\s*Inventaris|Kode(?:\s*(?:Barang|Aset|Fasilitas|Inventaris))?|ID(?:[-\s]*(?:Barang|Aset|Fasilitas|FAS|Fas))?)';

        // Kasus umum: "<Label>: <kode>. <LabelBerikutnya>:" — berhenti pas ketemu titik
        // yang diikuti label baru (kata berawalan huruf besar + titik dua), misal ". Merk:"
        if (preg_match('/' . $labelPattern . '\s*:\s*(.*?)\.\s*(?=[A-Z][a-zA-Z]*\s*:)/s', $deskripsi, $match)) {
            $kode = trim($match[1]);
            if ($kode !== '') return $kode;
        }

        // Fallback ke-2: gak ada label lain setelahnya, ambil sampai titik pertama atau akhir teks
        if (preg_match('/' . $labelPattern . '\s*:\s*([^.]+)/s', $deskripsi, $match)) {
            $kode = trim($match[1]);
            if ($kode !== '') return $kode;
        }

        // Fallback ke-3: kode nempel LANGSUNG tanpa label apapun di depannya, misal:
        // "Kondisi: Baik. ID-FAS-SF-A01. Merk: FujiFilm. Jumlah: 1 unit."
        // Cirinya: huruf besar + angka digabung tanda strip, minimal 3 segmen
        // (biar gak salah tangkep model produk biasa kayak "OHAUS PX224" yang gak ada strip-nya).
        if (preg_match('/\b[A-Z0-9]{2,}(?:-[A-Z0-9]{1,10}){2,}\b/', $deskripsi, $match)) {
            return $match[0];
        }

        return null;
    }

    private function validateRow(array $row, int $lineNumber): void
    {
        if (empty($row['nama_barang'])) {
            throw new \Exception("Kolom 'nama_barang' tidak boleh kosong.");
        }
        if (strlen($row['nama_barang']) > 100) {
            throw new \Exception("Nama barang terlalu panjang (maks 100 karakter).");
        }
        if (empty($row['tempat_utama'])) {
            throw new \Exception("Kolom 'lokasi' tidak boleh kosong.");
        }
        if (empty($row['deskripsi'])) {
            throw new \Exception("Kolom 'deskripsi' tidak boleh kosong.");
        }
    }

    // Catatan: method ini sudah TIDAK dipakai lagi oleh import() (sekarang bulk insert).
    // Dibiarkan di sini kalau-kalau suatu saat butuh simpan 1 baris tunggal.
    private function saveRow(array $row, int $importedBy): void
    {
        $userId = $importedBy;
        if (!empty($row['_email_pelapor'])) {
            $user = User::where('email', $row['_email_pelapor'])->first();
            if ($user) $userId = $user->id;
        }

        $nomorTiket  = 'SMTFAS-IMP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $tempatLabel = $row['tempat_label'] ?? ucwords(str_replace('_', ' ', $row['tempat_utama'] ?? 'Tidak Diketahui'));

        if (!empty($row['sub_lokasi'])) {
            $tempatLabel .= ' - ' . ucwords(str_replace('_', ' ', $row['sub_lokasi']));
        }

        $laporan = Laporan::create([
            'user_id'           => $userId,
            'nomor_tiket'       => $nomorTiket,
            'nama_barang'       => $row['nama_barang'],
            'kode_barang'       => $row['kode_barang'] ?? null,
            'jenis_barang'      => $row['jenis_barang'] ?? 'non-elektronik',
            'tempat_utama'      => $row['tempat_utama'],
            'sub_lokasi'        => $row['sub_lokasi'] ?? null,
            'tempat_label'      => $tempatLabel,
            'kegiatan_akademik' => $row['kegiatan_akademik'] ?? null,
            'deskripsi'         => $row['deskripsi'] ?? '-',
            'status'            => $row['status'] ?? 'menunggu',
            'tingkat_kerusakan' => $row['tingkat_kerusakan'] ?? null,
            'total_skor'        => 0,
            'prioritas'         => 'rendah',
        ]);

        $skor    = $laporan->hitungSkor();
        $tingkat = $row['tingkat_kerusakan'] ?? ($skor >= 70 ? 'tinggi' : ($skor >= 40 ? 'sedang' : 'rendah'));

        $laporan->update([
            'total_skor'        => $skor,
            'tingkat_kerusakan' => $tingkat,
            'prioritas'         => $laporan->getPrioritasLabel(),
        ]);
    }

    // ── Helper preview ────────────────────────────────────────────────────
    // $limit = null -> map SEMUA baris (dipakai untuk pagination di controller)
    // $limit = angka -> map cuma sejumlah itu (kalau suatu saat perlu preview cepat)
    public function previewMapping(array $rows, ?int $limit = null): array
    {
        $slice = $limit ? array_slice($rows, 0, $limit) : $rows;
        return array_map(fn($row) => $this->mapRow($row), $slice);
    }

    public function deteksiKolom(array $rows): array
    {
        if (empty($rows)) return [];

        $headers      = array_keys($rows[0]);
        $terdeteksi   = [];
        $tidakDikenal = [];

        foreach ($headers as $header) {
            $key    = $this->cleanHeader($header);
            $target = $this->columnMap[$key] ?? $this->guessColumnFallback($key);

            if ($target) {
                $terdeteksi[$header] = $target;
            } else {
                $tidakDikenal[] = $header;
            }
        }

        return compact('terdeteksi', 'tidakDikenal');
    }
}