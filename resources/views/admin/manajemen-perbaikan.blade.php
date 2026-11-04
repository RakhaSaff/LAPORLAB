@extends('layouts.admin')

@section('title', 'Manajemen Perbaikan — LAPORLAB Admin')
@section('page-title', 'Manajemen Perbaikan')
@section('page-subtitle', 'Laporan terverifikasi & pengelolaan status perbaikan')

@section('content')

{{-- Summary mini cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card blue fade-in fade-in-1">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $countDiproses ?? 0 }}</p>
                <p class="text-xs text-gray-500">Sedang Diproses</p>
            </div>
        </div>
    </div>
    <div class="stat-card green fade-in fade-in-2">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $countSelesai ?? 0 }}</p>
                <p class="text-xs text-gray-500">Selesai Diperbaiki</p>
            </div>
        </div>
    </div>
    <div class="stat-card orange fade-in fade-in-3">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ ($countDiproses ?? 0) + ($countSelesai ?? 0) }}</p>
                <p class="text-xs text-gray-500">Total Terverifikasi</p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Table --}}
<div class="card fade-in fade-in-2">
    <div class="card-header">
        <div>
            <p class="card-title">Laporan Terverifikasi</p>
            <p class="text-xs text-gray-400 mt-0.5">Kelola status & data perbaikan teknisi</p>
        </div>
        <div class="flex gap-2">
            <select onchange="filterStatus(this.value)"
                    class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none focus:border-emerald-400 bg-white">
                <option value="">Semua Status</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Fasilitas / Barang</th>
                    <th>Tingkat</th>
                    {{-- ✅ Kolom Prioritas Urgensi dihapus dari tampilan --}}
                    {{-- total_skor & prioritas tetap ada di DB & ORDER BY tetap jalan --}}
                    <th>Status Laporan</th>
                    <th>Data Perbaikan</th>
                    <th>Status Perbaikan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan ?? [] as $item)
                @php $prioritas = strtolower($item->prioritas ?? 'rendah') @endphp
                {{-- Warna baris tetap berdasarkan prioritas (subtle, tidak ada label) --}}
                <tr class="{{ $prioritas === 'tinggi' ? 'bg-red-50/40' : ($prioritas === 'sedang' ? 'bg-yellow-50/40' : '') }}">
                    <td class="text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>
                    <td>
                        <div class="font-semibold text-sm text-gray-800">{{ $item->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-gray-400">{{ $item->tempat_label ?? $item->tempat_utama }}</div>
                        @if($item->kode_barang)
                            <div class="text-[11px] text-gray-400 font-mono mt-0.5">No.Inv: {{ $item->kode_barang }}</div>
                        @endif
                    </td>
                    <td>
                        @if($item->tingkat_kerusakan === 'tinggi' || $item->tingkat_kerusakan === 'kritis')
                            <span class="badge badge-tinggi"><i class="fa-solid fa-circle-exclamation"></i> {{ ucfirst($item->tingkat_kerusakan) }}</span>
                        @elseif($item->tingkat_kerusakan === 'sedang')
                            <span class="badge badge-sedang"><i class="fa-solid fa-triangle-exclamation"></i> Sedang</span>
                        @else
                            <span class="badge badge-rendah"><i class="fa-solid fa-circle-info"></i> Rendah</span>
                        @endif
                    </td>

                    {{-- ✅ Kolom Prioritas Urgensi DIHAPUS dari sini --}}
                    {{-- Data total_skor & prioritas tetap tersimpan di DB --}}
                    {{-- Urutan baris tetap dikendalikan ORDER BY total_skor DESC di controller --}}

                    <td>
                        @if($item->status === 'diproses')
                            <span class="badge badge-diproses"><i class="fa-solid fa-rotate"></i> Diproses</span>
                        @else
                            <span class="badge badge-selesai"><i class="fa-solid fa-check"></i> Selesai</span>
                        @endif
                    </td>

                    {{-- Kolom Data Perbaikan --}}
                    <td class="text-xs text-gray-600">
                        @if($item->perbaikan)
                        <div class="space-y-0.5">
                            <div><span class="text-gray-400">Teknisi:</span> <span class="font-semibold">{{ $item->perbaikan->nama_teknisi }}</span></div>
                            <div><span class="text-gray-400">Tanggal:</span> {{ \Carbon\Carbon::parse($item->perbaikan->tanggal_perbaikan)->format('d M Y') }}</div>
                            @if($item->perbaikan->kode_inv)
                            <div><span class="text-gray-400">Kode INV:</span> <span class="font-mono">{{ $item->perbaikan->kode_inv }}</span></div>
                            @endif
                            <div class="text-gray-400 italic line-clamp-1">{{ $item->perbaikan->keterangan }}</div>
                            @if($item->perbaikan->bukti_foto)
                            <button onclick="openPhoto('{{ asset('storage/' . $item->perbaikan->bukti_foto) }}')"
                                    class="text-blue-500 hover:text-blue-700 font-medium flex items-center gap-1 mt-1">
                                <i class="fa-solid fa-image"></i> Lihat Foto
                            </button>
                            @endif
                        </div>
                        @else
                        <button onclick="openModalPerbaikan({{ $item->id }}, '{{ addslashes($item->kode_barang ?? '') }}', '{{ addslashes($item->nama_barang) }}', '', '')"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition">
                            <i class="fa-solid fa-plus-circle"></i> Isi Data
                        </button>
                        @endif
                    </td>

                    {{-- Status Perbaikan --}}
                    <td>
                        @if($item->perbaikan)
                            @if($item->perbaikan->status_perbaikan === 'selesai')
                                <span class="badge badge-selesai">Selesai</span>
                            @else
                                <span class="badge badge-diproses">Diproses</span>
                            @endif
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="flex flex-col gap-1.5">
                            @if($item->status !== 'selesai')
                            <form method="POST" action="{{ route('admin.manajemen-perbaikan.selesai', $item->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition w-full justify-center">
                                    <i class="fa-solid fa-check-double"></i> Tandai Selesai
                                </button>
                            </form>
                            @endif
                            <button onclick="openModalPerbaikan({{ $item->id }}, '{{ addslashes($item->kode_barang ?? '') }}', '{{ addslashes($item->nama_barang) }}', '{{ addslashes(optional($item->perbaikan)->barang_diperbaiki ?? '') }}', '{{ addslashes(optional($item->perbaikan)->kode_inv ?? '') }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition w-full justify-center">
                                <i class="fa-solid fa-pen"></i>
                                {{ $item->perbaikan ? 'Edit Perbaikan' : 'Input Perbaikan' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-14 text-gray-400">
                        <i class="fa-solid fa-screwdriver-wrench text-4xl mb-3 block text-gray-200"></i>
                        <p class="font-medium">Belum ada laporan terverifikasi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($laporan) && $laporan->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $laporan->links() }}</div>
    @endif
</div>

{{-- Modal Input Perbaikan --}}
<div id="modalPerbaikan"
     class="fixed inset-0 bg-black/50 z-[200] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-base font-bold text-gray-900">Data Perbaikan Teknisi</h3>
            <button onclick="closeModalPerbaikan()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" id="formPerbaikan" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf @method('POST')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Teknisi</label>
                <input type="text" name="nama_teknisi" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Perbaikan</label>
                <input type="date" name="tanggal_perbaikan" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Barang / Fasilitas yang Diperbaiki</label>
                <input type="text" name="barang_diperbaiki" id="inputBarangDiperbaiki" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode INV</label>
                <input type="text" name="kode_inv" id="inputKodeInv"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
                <p class="text-[11px] text-gray-400 mt-1">Terisi otomatis dari data laporan (No.Inv) jika ada. Bisa diedit kalau perlu.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti Foto Perbaikan</label>
                <input type="file" name="bukti_foto" accept="image/*"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status Perbaikan</label>
                <select name="status_perbaikan" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 bg-white">
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModalPerbaikan()"
                        class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Photo --}}
<div id="photoModal" onclick="closePhoto()"
     class="fixed inset-0 bg-black/70 z-[300] hidden items-center justify-center p-6">
    <img id="photoModalImg" src="" class="max-w-2xl max-h-[80vh] rounded-xl shadow-2xl object-contain">
</div>

@endsection

@push('scripts')
<script>
function filterStatus(val) {
    const url = new URL(window.location.href);
    if (val) url.searchParams.set('status_filter', val);
    else url.searchParams.delete('status_filter');
    window.location.href = url.toString();
}

/**
 * @param {number} id            ID laporan
 * @param {string} kodeBarang    kode_barang laporan (No.Inv), bisa kosong
 * @param {string} namaBarang    nama_barang laporan
 * @param {string} existingBarang isi barang_diperbaiki yang sudah tersimpan (mode edit), kosong kalau data baru
 * @param {string} existingKodeInv isi kode_inv yang sudah tersimpan (mode edit), kosong kalau data baru
 */
function openModalPerbaikan(id, kodeBarang, namaBarang, existingBarang, existingKodeInv) {
    document.getElementById('formPerbaikan').action = `/admin/manajemen-perbaikan/${id}/perbaikan`;

    const barangField = document.getElementById('inputBarangDiperbaiki');
    const kodeField    = document.getElementById('inputKodeInv');

    if (existingBarang) {
        // Mode edit: data perbaikan sudah pernah diisi, tampilkan apa adanya
        barangField.value = existingBarang;
        kodeField.value   = existingKodeInv || '';
    } else {
        // Data baru: autofill dari nama_barang & kode_barang (No.Inv) hasil import/laporan,
        // tapi taruh di 2 field terpisah (bukan digabung jadi satu teks)
        barangField.value = namaBarang;
        kodeField.value   = kodeBarang || '';
    }

    document.getElementById('modalPerbaikan').classList.remove('hidden');
    document.getElementById('modalPerbaikan').classList.add('flex');
}

function closeModalPerbaikan() {
    document.getElementById('modalPerbaikan').classList.add('hidden');
    document.getElementById('modalPerbaikan').classList.remove('flex');
}
function openPhoto(src) {
    document.getElementById('photoModalImg').src = src;
    document.getElementById('photoModal').classList.remove('hidden');
    document.getElementById('photoModal').classList.add('flex');
}
function closePhoto() {
    document.getElementById('photoModal').classList.add('hidden');
    document.getElementById('photoModal').classList.remove('flex');
}
</script>
@endpush