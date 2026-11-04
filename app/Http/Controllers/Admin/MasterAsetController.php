<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterAset;
use Illuminate\Http\Request;

class MasterAsetController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterAset::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_barang', 'like', "%{$s}%")
                  ->orWhere('kode_barang', 'like', "%{$s}%")
                  ->orWhere('merk', 'like', "%{$s}%");
            });
        }

        if ($request->filled('jenis_aset')) {
            $query->where('jenis_aset', $request->jenis_aset);
        }

        $asets                 = $query->orderBy('nama_barang')->paginate(15)->withQueryString();
        $totalAset             = MasterAset::count();
        $totalBarangTetap      = MasterAset::where('jenis_aset', 'barang_tetap')->count();
        $totalBarangTidakTetap = MasterAset::where('jenis_aset', 'barang_tidak_tetap')->count();

        return view('admin.master-aset.index', compact(
            'asets', 'totalAset', 'totalBarangTetap', 'totalBarangTidakTetap'
        ));
    }

    public function create()
    {
        return view('admin.master-aset.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Generate kode otomatis kalau kosong dan jenis_aset diisi (fallback)
        if (empty($data['kode_barang']) && !empty($data['jenis_aset'])) {
            $data['kode_barang'] = $this->buatKode($data['jenis_aset']);
        }

        MasterAset::create($data);

        return redirect()->route('admin.master-aset.index')
                         ->with('success', 'Aset berhasil ditambahkan ke Master Data.');
    }

    public function edit($id)
    {
        $aset = MasterAset::findOrFail($id);
        return view('admin.master-aset.edit', compact('aset'));
    }

    public function update(Request $request, $id)
    {
        $aset = MasterAset::findOrFail($id);
        $data = $this->validateData($request);
        $aset->update($data);

        return redirect()->route('admin.master-aset.index')
                         ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aset = MasterAset::findOrFail($id);
        $aset->delete();

        return back()->with('success', 'Aset berhasil dihapus dari Master Data.');
    }

    // ── Generate kode via AJAX ────────────────────────────────────────────
    public function generateKode(Request $request)
    {
        $jenis = $request->query('jenis', 'barang_tetap');
        $kode  = $this->buatKode($jenis);

        return response()->json(['kode' => $kode]);
    }

    // ── Logic generate kode ───────────────────────────────────────────────
    private function buatKode(string $jenis): string
    {
        $prefix = $jenis === 'barang_tetap' ? 'BT' : 'BTT';
        $tahun  = date('Y');

        // Hitung jumlah aset dengan prefix yang sama di tahun ini
        $count = MasterAset::where('kode_barang', 'like', "SMTFAS-{$prefix}-{$tahun}-%")
                            ->count();

        $urutan = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        // Format: SMTFAS-BT-2026-001
        $kode = "SMTFAS-{$prefix}-{$tahun}-{$urutan}";

        // Pastikan unik
        while (MasterAset::where('kode_barang', $kode)->exists()) {
            $urutan = str_pad((int)$urutan + 1, 3, '0', STR_PAD_LEFT);
            $kode   = "SMTFAS-{$prefix}-{$tahun}-{$urutan}";
        }

        return $kode;
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'kode_barang' => 'nullable|string|max:100',
            'nama_barang' => 'required|string|max:150',
            // jenis_aset tidak lagi wajib
            'jenis_aset'  => 'nullable|in:barang_tetap,barang_tidak_tetap',
            'kategori'    => 'nullable|string|max:100',
            'merk'        => 'nullable|string|max:150',
            'jumlah'      => 'nullable|integer|min:0',
            'keadaan'     => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'jenis_aset.in'        => 'Jenis aset tidak valid.',
            'jumlah.integer'       => 'Jumlah harus berupa angka.',
        ]);
    }
}