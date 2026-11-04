<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Events\StatusLaporanUpdated;
use Illuminate\Http\Request;

class ManajemenPerbaikanController extends Controller
{
    /**
     * Tampilkan laporan terverifikasi diurutkan berdasarkan skor urgensi tertinggi.
     * ORDER BY total_skor DESC, created_at ASC
     * → Laporan dengan skor tertinggi (prioritas tinggi/sedang) muncul paling atas.
     */
    public function index()
    {
        $laporan = Laporan::with(['user', 'perbaikan'])
                          ->where('status', 'diproses')
                          ->orderByDesc('total_skor')   // ✅ Prioritas tertinggi di atas
                          ->orderBy('created_at')       // ✅ Jika skor sama, yang lama duluan
                          ->paginate(15);

        $countDiproses = Laporan::where('status', 'diproses')->count();
        $countSelesai  = Laporan::where('status', 'selesai')->count();

        return view('admin.manajemen-perbaikan', compact('laporan', 'countDiproses', 'countSelesai'));
    }

    public function selesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update(['status' => 'selesai']);

        broadcast(new StatusLaporanUpdated($laporan));

        return back()->with('success', 'Status laporan diubah menjadi selesai.');
    }

    public function storePerbaikan(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $data = $request->validate([
            'nama_teknisi'      => 'required|string',
            'tanggal_perbaikan' => 'required|date',
            'barang_diperbaiki' => 'required|string',
            'kode_inv'          => 'nullable|string',
            'keterangan'        => 'nullable|string',
            'status_perbaikan'  => 'required|in:diproses,selesai',
            'bukti_foto'        => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('perbaikan', 'public');
        }

        $data['laporan_id'] = $laporan->id;

        if ($laporan->perbaikan) {
            $laporan->perbaikan->update($data);
        } else {
            \App\Models\Perbaikan::create($data);
        }

        if ($data['status_perbaikan'] === 'selesai') {
            $laporan->update(['status' => 'selesai']);
        } else {
            $laporan->update(['status' => 'diproses']);
        }

        broadcast(new StatusLaporanUpdated($laporan->fresh()));

        return back()->with('success', 'Data perbaikan berhasil disimpan.');
    }
}