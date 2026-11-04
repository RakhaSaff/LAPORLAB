<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImportMasterAsetService;
use Illuminate\Http\Request;

class ImportMasterAsetController extends Controller
{
    public function __construct(private ImportMasterAsetService $service) {}

    public function index()
    {
        return view('admin.master-aset.import');
    }

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

        $filename = uniqid('import_master_') . '.' . $extension;
        $tempDir  = storage_path('app' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . 'temp');

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $fullPath = $tempDir . DIRECTORY_SEPARATOR . $filename;
        $file->move($tempDir, $filename);

        try {
            $rows    = $this->service->parse($fullPath, $extension);
            $kolom   = $this->service->deteksiKolom($rows);
            $preview = $this->service->previewMapping($rows);
            $total   = count($rows);

            session([
                'import_master_file_path'      => $fullPath,
                'import_master_file_extension' => $extension,
            ]);

            return view('admin.master-aset.import-preview', compact(
                'preview', 'kolom', 'total', 'extension'
            ));

        } catch (\Exception $e) {
            if (file_exists($fullPath)) unlink($fullPath);
            return back()->withInput()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $fullPath  = session('import_master_file_path');
        $extension = session('import_master_file_extension');

        if (!$fullPath || !file_exists($fullPath)) {
            return redirect()->route('admin.import-master-aset.index')
                ->with('error', 'Sesi import sudah habis. Silakan upload ulang file.');
        }

        try {
            $rows   = $this->service->parse($fullPath, $extension);
            $result = $this->service->import($rows);

            if (file_exists($fullPath)) unlink($fullPath);
            session()->forget(['import_master_file_path', 'import_master_file_extension']);

            return redirect()->route('admin.master-aset.index')
                ->with('import_result', $result);

        } catch (\Exception $e) {
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        $fullPath = session('import_master_file_path');
        if ($fullPath && file_exists($fullPath)) unlink($fullPath);
        session()->forget(['import_master_file_path', 'import_master_file_extension']);

        return redirect()->route('admin.import-master-aset.index')->with('info', 'Import dibatalkan.');
    }
}