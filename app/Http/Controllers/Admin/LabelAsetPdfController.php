<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabelAset;
use Illuminate\Support\Facades\Storage;

class LabelAsetPdfController extends Controller
{
    public function download($id)
    {
        $labelAset = LabelAset::findOrFail($id);
        $labels    = json_decode($labelAset->labels, true);
        $fotoPath  = $labelAset->foto_path;

        // Convert foto ke base64 untuk embed di PDF
        $fotoBase64 = '';
        if (Storage::disk('public')->exists($fotoPath)) {
            $fotoContent = Storage::disk('public')->get($fotoPath);
            $mime        = Storage::disk('public')->mimeType($fotoPath);
            $fotoBase64  = 'data:' . $mime . ';base64,' . base64_encode($fotoContent);
        }

        $html = view('admin.label-aset.pdf', compact('labels', 'labelAset', 'fotoBase64'))->render();

        // Gunakan DomPDF jika ada, fallback ke HTML download
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
            return $pdf->download('label-aset-' . $labelAset->id . '.pdf');
        }

        // Fallback: download sebagai HTML yang bisa diprint ke PDF
        return response($html, 200, [
            'Content-Type'        => 'text/html',
            'Content-Disposition' => 'attachment; filename="label-aset-' . $labelAset->id . '.html"',
        ]);
    }
}
