@extends('layouts.admin')

@section('title', 'Laporan Masuk — LAPORLAB Admin')
@section('page-title', 'Laporan Masuk')
@section('page-subtitle', 'Kelola & verifikasi laporan kerusakan fasilitas')

@section('content')

@if(session('import_result'))
    @php $ir = session('import_result'); @endphp
    @if(($ir['gagal'] ?? 0) > 0)
    <div class="mb-4 px-4 py-3 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm">
        <div class="flex items-center gap-2 font-medium">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Import selesai: <strong>{{ $ir['berhasil'] }}</strong> berhasil, <strong>{{ $ir['gagal'] }}</strong> gagal.
        </div>
        @if(!empty($ir['errors']))
        <details class="mt-2">
            <summary class="cursor-pointer text-xs font-semibold text-amber-700">Lihat detail baris yang gagal ({{ count($ir['errors']) }})</summary>
            <ul class="mt-2 text-xs list-disc list-inside space-y-0.5 max-h-40 overflow-y-auto">
                @foreach(array_slice($ir['errors'], 0, 50) as $err)
                    <li>{{ $err }}</li>
                @endforeach
                @if(count($ir['errors']) > 50)
                    <li>... dan {{ count($ir['errors']) - 50 }} error lainnya.</li>
                @endif
            </ul>
        </details>
        @endif
    </div>
    @else
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        Import selesai: <strong>{{ $ir['berhasil'] }}</strong> laporan berhasil ditambahkan.
    </div>
    @endif
@endif

{{-- Filter & Search --}}
<div class="card mb-6 fade-in fade-in-1">
    <div class="p-5 flex flex-wrap gap-3 items-center justify-between">
        <div class="flex flex-wrap gap-3">
            <select name="tingkat" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:border-emerald-400 bg-white">
                <option value="">Semua Tingkat</option>
                <option value="baik" {{ request('tingkat') == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="perlu_pemeliharaan" {{ request('tingkat') == 'perlu_pemeliharaan' ? 'selected' : '' }}>Perlu Pemeliharaan</option>
                <option value="rusak_ringan" {{ request('tingkat') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak_sedang" {{ request('tingkat') == 'rusak_sedang' ? 'selected' : '' }}>Rusak Sedang</option>
                <option value="rusak_berat" {{ request('tingkat') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                <option value="rusak_total" {{ request('tingkat') == 'rusak_total' ? 'selected' : '' }}>Rusak Total</option>
                <option value="tidak_layak_pakai" {{ request('tingkat') == 'tidak_layak_pakai' ? 'selected' : '' }}>Tidak Layak Pakai</option>
                <option value="hilang" {{ request('tingkat') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                <option value="rendah" {{ request('tingkat') == 'rendah' ? 'selected' : '' }}>Rendah (data lama)</option>
                <option value="sedang" {{ request('tingkat') == 'sedang' ? 'selected' : '' }}>Sedang (data lama)</option>
                <option value="tinggi" {{ request('tingkat') == 'tinggi' ? 'selected' : '' }}>Tinggi (data lama)</option>
            </select>

            <select name="status" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:border-emerald-400 bg-white">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari laporan..."
                   class="text-sm border border-gray-200 rounded-lg px-3 py-2 w-56 focus:outline-none focus:border-emerald-400">
            <button type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card fade-in fade-in-2">
    <div class="card-header">
        <div>
            <p class="card-title">Daftar Laporan Masuk</p>
            <p class="text-xs text-gray-400 mt-0.5">Total {{ $laporan->total() ?? 0 }} laporan</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Fasilitas / Barang</th>
                    <th>No.Inv</th>
                    <th>Lokasi</th>
                    <th>Foto</th>
                    <th>Tingkat Kerusakan</th>
                    <th>Status</th>
                    <th>Waktu Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan ?? [] as $item)
                <tr>
                    <td class="text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>
                    <td>
                        <div class="font-semibold text-sm text-gray-800">{{ $item->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $item->user->email }}</div>
                    </td>
                    <td>
                        <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-gray-400">{{ $item->jenis_barang }}</div>
                    </td>
                    <td class="text-sm text-gray-700 font-mono">
                        {{ $item->kode_barang ?? '-' }}
                    </td>
                    <td class="text-sm text-gray-600">
                        {{ ucwords(str_replace('_', ' ', $item->tempat_label ?? $item->tempat_utama ?? '-')) }}
                    </td>
                    <td>
                        @php
                            $fotoPreview = $item->foto_atas ?? $item->foto_depan ?? $item->foto_kanan ??
                                           $item->foto_kiri ?? $item->foto_bawah ?? $item->foto_belakang ?? null;
                        @endphp
                        @if($fotoPreview)
                            <img src="{{ asset('storage/' . $fotoPreview) }}"
                                 class="w-14 h-10 object-cover rounded-lg border cursor-pointer hover:opacity-80 transition"
                                 onclick="openPhoto('{{ asset('storage/' . $fotoPreview) }}')">
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $item->tingkat_kerusakan_badge }}">
                            <i class="fa-solid fa-circle-info"></i> {{ $item->tingkat_kerusakan_label }}
                        </span>
                    </td>
                    <td>
                        @if($item->status === 'menunggu')
                            <span class="badge badge-pending">Menunggu</span>
                        @elseif($item->status === 'diverifikasi')
                            <span class="badge badge-diproses">Diverifikasi</span>
                        @elseif($item->status === 'diproses')
                            <span class="badge badge-diproses">Diproses</span>
                        @elseif($item->status === 'ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-selesai">Selesai</span>
                        @endif
                    </td>
                    <td class="text-xs text-gray-500">
                        <div class="font-medium">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</div>
                    </td>
                    <td>
                        <div class="flex flex-col gap-1.5">
                            <a href="{{ route('admin.laporan-masuk.show', $item->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition justify-center">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                            @if($item->status === 'menunggu')
                            <form method="POST" action="{{ route('admin.laporan-masuk.verifikasi', $item->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition w-full justify-center">
                                    <i class="fa-solid fa-check"></i> Verifikasi
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.laporan-masuk.destroy', $item->id) }}"
                                  onsubmit="return confirm('Yakin mau hapus laporan &quot;{{ addslashes($item->nama_barang) }}&quot;? Aksi ini tidak bisa dibatalkan.');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition w-full justify-center">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-14 text-gray-400">
                        <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                        <p class="font-medium">Belum ada laporan masuk</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($laporan) && $laporan->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $laporan->links() }}
    </div>
    @endif
</div>

{{-- Modal Photo --}}
<div id="photoModal" onclick="closePhoto()"
     class="fixed inset-0 bg-black/70 z-[200] hidden items-center justify-center p-6">
    <img id="photoModalImg" src="" class="max-w-2xl max-h-[80vh] rounded-xl shadow-2xl object-contain">
</div>

@endsection

@push('scripts')
<script>
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