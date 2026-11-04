<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Events\StatusLaporanUpdated;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index()
    {
        $laporan = Laporan::with('user')
                          ->where('status', 'menunggu')
                          ->orderByDesc('created_at')
                          ->paginate(15);
        return view('admin.perlu-verifikasi', compact('laporan'));
    }

    public function proses(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status'            => 'diproses',
            'tingkat_kerusakan' => $request->tingkat_kerusakan,
        ]);

        broadcast(new StatusLaporanUpdated($laporan));

        return back()->with('success', 'Laporan berhasil diverifikasi.');
    }
}