<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with('user')->orderByDesc('created_at');

        if ($request->tingkat) {
            $query->where('tingkat_kerusakan', $request->tingkat);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($u) use ($request) {
                      $u->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $laporan = $query->paginate(15);
        return view('admin.laporan-masuk', compact('laporan'));
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('admin.laporan-masuk-detail', compact('laporan'));
    }

    /**
     * Update status manual dari halaman Detail Laporan.
     *
     * Status "selesai" TIDAK boleh diatur dari sini. Status itu hanya berubah
     * lewat Manajemen Perbaikan (tombol "Tandai Selesai" / form Data Perbaikan),
     * supaya status laporan selalu sinkron dengan data perbaikan teknisi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diverifikasi,diproses,selesai,ditolak',
        ]);

        $laporan = Laporan::findOrFail($id);

        // Pengaman 1: tolak kalau ada yang mencoba set "selesai" dari sini
        if ($request->status === 'selesai') {
            return back()->with('error', 'Status "Selesai" hanya bisa diatur lewat menu Manajemen Perbaikan.');
        }

        // Pengaman 2: laporan yang sudah selesai tidak boleh diubah dari sini
        if ($laporan->status === 'selesai') {
            return back()->with('error', 'Laporan ini sudah selesai. Status diatur lewat menu Manajemen Perbaikan.');
        }

        $laporan->status = $request->status;
        $laporan->save();

        return redirect()->route('admin.laporan-masuk.show', $id)
                         ->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Verifikasi laporan + hitung ulang skor prioritas.
     *
     * tingkat_kerusakan sekarang diisi oleh pelapor lewat form (8 pilihan),
     * jadi method ini TIDAK menimpanya. Nilai itu hanya dipakai sebagai salah
     * satu komponen perhitungan skor di Laporan::hitungSkor().
     */
    public function verifikasi(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        // Laporan yang sudah selesai tidak boleh "dihidupkan" lagi lewat verifikasi
        if ($laporan->status === 'selesai') {
            return back()->with('error', 'Laporan ini sudah selesai dan tidak bisa diverifikasi ulang.');
        }

        // Opsional: kalau suatu saat admin mengirim tingkat_kerusakan, validasi dulu
        if ($request->filled('tingkat_kerusakan')) {
            $request->validate([
                'tingkat_kerusakan' => 'in:baik,perlu_pemeliharaan,rusak_ringan,rusak_sedang,rusak_berat,rusak_total,tidak_layak_pakai,hilang',
            ]);
            $laporan->tingkat_kerusakan = $request->tingkat_kerusakan;
        }

        $skor                = $laporan->hitungSkor();
        $laporan->total_skor = $skor;
        $laporan->prioritas  = $laporan->getPrioritasLabel();
        $laporan->status     = 'diproses';
        $laporan->save();

        return back()->with('success', 'Laporan diverifikasi. Prioritas: ' . ucfirst($laporan->prioritas) . '.');
    }

    /**
     * Hapus laporan (status apapun) beserta foto & data perbaikan terkait,
     * kalau ada. Dipakai buat buang data testing/salah upload dari Laporan Masuk,
     * tanpa harus nunggu laporan itu berstatus 'selesai' dulu (beda dari Rekapan).
     */
    public function destroy($id)
    {
        $laporan = Laporan::with('perbaikan')->findOrFail($id);

        // Hapus foto bukti kerusakan + foto kode barang
        $kolomFoto = [
            'foto_atas', 'foto_bawah', 'foto_kanan',
            'foto_kiri', 'foto_depan', 'foto_belakang',
            'foto_kode',
        ];
        foreach ($kolomFoto as $field) {
            if ($laporan->$field) {
                Storage::disk('public')->delete($laporan->$field);
            }
        }

        // Hapus data perbaikan terkait (kalau ada), termasuk foto bukti perbaikannya
        if ($laporan->perbaikan) {
            if ($laporan->perbaikan->bukti_foto) {
                Storage::disk('public')->delete($laporan->perbaikan->bukti_foto);
            }
            $laporan->perbaikan->delete();
        }

        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}