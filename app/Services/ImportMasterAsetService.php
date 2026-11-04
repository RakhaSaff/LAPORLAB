<?php

namespace App\Services;

use App\Models\MasterAset;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportMasterAsetService
{
    private array $columnMap = [
        // kode_barang
        'kode_barang'      => 'kode_barang',
        'kode barang'      => 'kode_barang',
        'id_barang'        => 'kode_barang',
        'id barang'        => 'kode_barang',
        'kode_aset'        => 'kode_barang',
        'kode aset'        => 'kode_barang',
        'no_inv'           => 'kode_barang',
        'no inv'           => 'kode_barang',
        'no.inv'           => 'kode_barang',
        'no. inventarisasi'=> 'kode_barang',
        'no inventarisasi' => 'kode_barang',
        'nomor_inventaris' => 'kode_barang',
        'nomor inventaris' => 'kode_barang',
        'nomor_referensi'  => 'kode_barang',
        'nomor referensi'  => 'kode_barang',
        'no_referensi'     => 'kode_barang',
        'no referensi'     => 'kode_barang',
        'referensi'        => 'kode_barang',
        'reference'        => 'kode_barang',
        'ref'              => 'kode_barang',
        'kode'             => 'kode_barang',

        // nama_barang
        'nama_barang'      => 'nama_barang',
        'nama barang'      => 'nama_barang',
        'nama'             => 'nama_barang',
        'barang'           => 'nama_barang',
        'nama_aset'        => 'nama_barang',
        'nama aset'        => 'nama_barang',
        'nama alat'        => 'nama_barang',
        'nama_alat'        => 'nama_barang',
        'fasilitas'        => 'nama_barang',
        'item'             => 'nama_barang',

        // kategori
        'jenis_barang'     => 'kategori',
        'jenis barang'     => 'kategori',
        'kategori'         => 'kategori',
        'category'         => 'kategori',
        'jenis'            => 'kategori',
        'tipe'             => 'kategori',
        'type'             => 'kategori',

        // lokasi
        'tempat_utama'     => 'lokasi',
        'tempat utama'     => 'lokasi',
        'lokasi'           => 'lokasi',
        'location'         => 'lokasi',
        'tempat'           => 'lokasi',
        'ruangan'          => 'lokasi',
        'ruang'            => 'lokasi',
        'gedung'           => 'lokasi',

        // sub_lokasi (digabung ke lokasi)
        'sub_lokasi'       => 'sub_lokasi',
        'sub lokasi'       => 'sub_lokasi',
        'detail_lokasi'    => 'sub_lokasi',
        'detail lokasi'    => 'sub_lokasi',

        // spesifikasi
        'deskripsi'        => 'spesifikasi',
        'description'      => 'spesifikasi',
        'keterangan'       => 'spesifikasi',
        'catatan'          => 'spesifikasi',
        'spesifikasi'      => 'spesifikasi',
        'specification'    => 'spesifikasi',
        'notes'            => 'spesifikasi',
        'detail'           => 'spesifikasi',
        'merk'             => 'spesifikasi',
        'merek'            => 'spesifikasi',
        'brand'            => 'spesifikasi',

        // tahun_perolehan
        'tahun_perolehan'  => 'tahun_perolehan',
        'tahun perolehan'  => 'tahun_perolehan',
        'tahun'            => 'tahun_perolehan',
        'year'             => 'tahun_perolehan',
        'tahun_pengadaan'  => 'tahun_perolehan',

        // jenis_aset
        'jenis_aset'       => 'jenis_aset',
        'jenis aset'       => 'jenis_aset',
    ];

    private function cleanHeader($header): string
    {
        $h = (string) $header;
        $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
        $h = preg_replace('/^\x{FEFF}/u', '', $h);
        $h = str_replace("\xC2\xA0", ' ', $h);
        $h = preg_replace('/\s+/', ' ', $h);
        return strtolower(trim($h));
    }

    public function parse(string $filePath, string $extension): array
    {
        return match (strtolower($extension)) {
            'xlsx', 'xls' => $this->parseSpreadsheet($filePath),
            'csv'         => $this->parseCsv($filePath),
            default       => throw new \Exception("Format file .{$extension} tidak didukung. Gunakan Excel atau CSV."),
        };
    }

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

    public function deteksiKolom(array $rows): array
    {
        if (empty($rows)) return ['terdeteksi' => [], 'tidakDikenal' => []];

        $headers      = array_keys($rows[0]);
        $terdeteksi   = [];
        $tidakDikenal = [];

        foreach ($headers as $header) {
            $key = $this->cleanHeader($header);
            if (isset($this->columnMap[$key])) {
                $terdeteksi[$header] = $this->columnMap[$key];
            } else {
                $tidakDikenal[] = $header;
            }
        }

        return compact('terdeteksi', 'tidakDikenal');
    }

    private function mapRow(array $rawRow): array
    {
        $mapped = [];

        foreach ($rawRow as $rawKey => $value) {
            $key      = $this->cleanHeader($rawKey);
            $smartKey = $this->columnMap[$key] ?? null;
            if ($smartKey) {
                $mapped[$smartKey] = $value !== '' ? $value : null;
            }
        }

        // Gabungin lokasi + sub_lokasi
        if (!empty($mapped['sub_lokasi'])) {
            $mapped['lokasi'] = trim(($mapped['lokasi'] ?? '') . ' - ' . $mapped['sub_lokasi']);
        }
        unset($mapped['sub_lokasi']);

        // Normalisasi jenis_aset — kalau tidak ada kolom jenis_aset di file,
        // default ke barang_tetap (karena data inventarisasi biasanya barang tetap)
        if (!empty($mapped['jenis_aset'])) {
            $v = strtolower(trim($mapped['jenis_aset']));
            $mapped['jenis_aset'] = str_contains($v, 'tidak') ? 'barang_tidak_tetap' : 'barang_tetap';
        } else {
            $mapped['jenis_aset'] = 'barang_tetap';
        }

        // Tahun perolehan: pastikan 4 digit angka
        if (!empty($mapped['tahun_perolehan']) && !preg_match('/^\d{4}$/', trim((string) $mapped['tahun_perolehan']))) {
            $mapped['tahun_perolehan'] = null;
        }

        return $mapped;
    }

    public function previewMapping(array $rows, ?int $limit = 5): array
    {
        $slice = $limit ? array_slice($rows, 0, $limit) : $rows;
        return array_map(fn($row) => $this->mapRow($row), $slice);
    }

    private function validateRow(array $row, int $lineNumber): void
    {
        if (empty($row['nama_barang'])) {
            throw new \Exception("Kolom 'nama_barang' / 'nama alat' tidak boleh kosong.");
        }
        if (strlen($row['nama_barang']) > 150) {
            throw new \Exception("Nama barang terlalu panjang (maks 150 karakter).");
        }
    }

    public function import(array $rows): array
    {
        $errors   = [];
        $toInsert = [];
        $now      = now();

        foreach ($rows as $index => $rawRow) {
            try {
                $mapped = $this->mapRow($rawRow);
                $this->validateRow($mapped, $index + 1);

                $toInsert[] = [
                    'kode_barang'      => $mapped['kode_barang'] ?? null,
                    'nama_barang'      => $mapped['nama_barang'],
                    'jenis_aset'       => $mapped['jenis_aset'],
                    'kategori'         => $mapped['kategori'] ?? null,
                    'lokasi'           => $mapped['lokasi'] ?? null,
                    'tahun_perolehan'  => $mapped['tahun_perolehan'] ?? null,
                    'spesifikasi'      => $mapped['spesifikasi'] ?? null,
                    'jumlah_kerusakan' => 0,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        $berhasil = 0;

        DB::transaction(function () use ($toInsert, &$berhasil) {
            foreach (array_chunk($toInsert, 200) as $chunk) {
                MasterAset::insert($chunk);
                $berhasil += count($chunk);
            }
        });

        $gagal = count($errors);

        return compact('berhasil', 'gagal', 'errors');
    }
}