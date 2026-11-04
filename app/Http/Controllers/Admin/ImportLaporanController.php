<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImportLaporanService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ImportLaporanController extends Controller
{
    public function __construct(private ImportLaporanService $service) {}

    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * Terima file upload, simpan ke storage sementara, lalu redirect
     * ke previewShow() (GET) supaya pagination bisa jalan tanpa upload ulang.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.mimes'    => 'Format file harus Excel (.xlsx/.xls) atau CSV.',
            'file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $file      = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $filename = uniqid('import_') . '.' . $extension;
        $tempDir  = storage_path('app' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . 'temp');

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $fullPath = $tempDir . DIRECTORY_SEPARATOR . $filename;
        $file->move($tempDir, $filename);

        // Validasi cepat: pastikan file bisa diparse sebelum lanjut
        try {
            $this->service->parse($fullPath, $extension);
        } catch (\Exception $e) {
            if (file_exists($fullPath)) unlink($fullPath);
            return back()->withInput()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        session([
            'import_file_path'      => $fullPath,
            'import_file_extension' => $extension,
        ]);

        return redirect()->route('admin.import.preview.show');
    }

    /**
     * Tampilkan preview data dengan pagination.
     * Query string yang didukung: ?page=2&per_page=25
     */
    public function previewShow(Request $request)
    {
        $fullPath  = session('import_file_path');
        $extension = session('import_file_extension');

        if (!$fullPath || !file_exists($fullPath)) {
            return redirect()->route('admin.import.index')
                ->with('error', 'Sesi import sudah habis. Silakan upload ulang file.');
        }

        try {
            $rows      = $this->service->parse($fullPath, $extension);
            $kolom     = $this->service->deteksiKolom($rows);
            $mappedAll = $this->service->previewMapping($rows); // semua baris, sudah di-mapping
            $total     = count($rows);

            $allowedPerPage = [5, 10, 25, 50, 100];
            $perPage = (int) $request->get('per_page', 25);
            if (!in_array($perPage, $allowedPerPage, true)) {
                $perPage = 25;
            }

            $currentPage = (int) $request->get('page', 1);
            if ($currentPage < 1) {
                $currentPage = 1;
            }

            $preview = new LengthAwarePaginator(
                array_slice($mappedAll, ($currentPage - 1) * $perPage, $perPage),
                count($mappedAll),
                $perPage,
                $currentPage,
                [
                    'path'  => $request->url(),
                    'query' => $request->query(),
                ]
            );

            return view('admin.import.preview', compact(
                'preview', 'kolom', 'total', 'extension', 'perPage', 'allowedPerPage'
            ));

        } catch (\Exception $e) {
            return redirect()->route('admin.import.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $fullPath  = session('import_file_path');
        $extension = session('import_file_extension');

        if (!$fullPath || !file_exists($fullPath)) {
            return redirect()->route('admin.import.index')
                ->with('error', 'Sesi import sudah habis. Silakan upload ulang file.');
        }

        try {
            $rows   = $this->service->parse($fullPath, $extension);
            $result = $this->service->import($rows, Auth::guard('admin')->id());

            if (file_exists($fullPath)) unlink($fullPath);
            session()->forget(['import_file_path', 'import_file_extension']);

            return redirect()->route('admin.laporan-masuk.index')
                ->with('import_result', $result);

        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $fullPath = session('import_file_path');
        if ($fullPath && file_exists($fullPath)) unlink($fullPath);
        session()->forget(['import_file_path', 'import_file_extension']);

        return redirect()->route('admin.import.index')->with('info', 'Import dibatalkan.');
    }

    public function downloadTemplate()
    {
        $templatePath = public_path('templates/template-import-laporan.xlsx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        return response()->download($templatePath, 'template-import-laporan-laporlab.xlsx');
    }
}