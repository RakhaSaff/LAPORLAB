@extends('layouts.admin')

@section('title', 'Detail Laporan — LAPORLAB Admin')
@section('page-title', 'Detail Laporan')
@section('page-subtitle', 'Informasi lengkap laporan kerusakan fasilitas')

@section('content')

{{-- Kembali --}}
<div class="mb-6">
    <a href="{{ route('admin.laporan-masuk.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-emerald-600 hover:border-emerald-300 transition shadow-sm">
        <i class="fa-solid fa-arrow-left text-xs"></i>
        Kembali ke Laporan Masuk
    </a>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium flex items-center gap-2">
    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- KIRI --}}
    <div class="lg:col-span-2 flex flex-col gap-6">

        {{-- Info Pelapor --}}
        <div class="card fade-in fade-in-1">
            <div class="card-header">
                <span class="card-title">
                    <i class="fa-solid fa-user text-emerald-500 mr-2"></i>Informasi Pelapor
                </span>
            </div>
            <div class="p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-lg shrink-0">
                    {{ strtoupper(substr($laporan->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900">{{ $laporan->user->name ?? '-' }}</div>
                    <div class="text-sm text-gray-400">{{ $laporan->user->email ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Detail Laporan --}}
        <div class="card fade-in fade-in-2">
            <div class="card-header">
                <span class="card-title">
                    <i class="fa-solid fa-file-lines text-emerald-500 mr-2"></i>Detail Laporan
                </span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Barang</p>
                        <p class="text-sm font-medium text-gray-800">{{ $laporan->nama_barang ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jenis Barang</p>
                        <p class="text-sm font-medium text-gray-800">{{ ucfirst($laporan->jenis_barang ?? '-') }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">No. Inventaris</p>
                        <p class="text-sm font-medium text-gray-800 font-mono">{{ $laporan->kode_barang ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lokasi</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ ucwords(str_replace('_', ' ', $laporan->tempat_label ?? $laporan->tempat_utama ?? '-')) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tingkat Kerusakan</p>
                        <span class="badge {{ $laporan->tingkat_kerusakan_badge }}">
                            <i class="fa-solid fa-circle-info"></i> {{ $laporan->tingkat_kerusakan_label }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                        @php $st = strtolower($laporan->status ?? '') @endphp
                        @if($st === 'selesai')
                            <span class="badge badge-selesai"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                        @elseif($st === 'diproses')
                            <span class="badge badge-diproses"><i class="fa-solid fa-rotate"></i> Diproses</span>
                        @elseif($st === 'diverifikasi')
                            <span class="badge badge-diproses"><i class="fa-solid fa-clipboard-check"></i> Diverifikasi</span>
                        @elseif($st === 'ditolak')
                            <span class="badge badge-tinggi"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
                        @else
                            <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Menunggu</span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Waktu Upload</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y, H:i') }} WIB
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($laporan->created_at)->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Prioritas --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Prioritas</p>
                        @php $p = strtolower($laporan->prioritas ?? '') @endphp
                        @if($p === 'tinggi')
                            <span class="badge badge-tinggi"><i class="fa-solid fa-circle-exclamation"></i> Tinggi</span>
                        @elseif($p === 'sedang')
                            <span class="badge badge-sedang"><i class="fa-solid fa-triangle-exclamation"></i> Sedang</span>
                        @elseif($p === 'rendah')
                            <span class="badge badge-rendah"><i class="fa-solid fa-circle-info"></i> Rendah</span>
                        @else
                            <span class="badge" style="background:#f1f5f9;color:#94a3b8;">
                                <i class="fa-solid fa-minus"></i> Belum Dihitung
                            </span>
                        @endif

                        @if($p === 'tinggi')
                        <div class="mt-2">
                            <p class="text-xs text-slate-400">Alasan Prioritas</p>
                            @if($laporan->kegiatan_akademik === 'ya')
                                <span class="text-red-600 text-sm font-medium">Ada kegiatan akademik berlangsung</span>
                            @elseif($laporan->kegiatan_akademik === 'tidak')
                                <span class="text-yellow-600 text-sm">Tidak ada kegiatan akademik</span>
                            @else
                                <span class="text-gray-400 text-sm">Fasilitas umum (toilet / musholla)</span>
                            @endif
                        </div>
                        @endif
                    </div>

                </div>

                {{-- Deskripsi --}}
                <div class="mt-6">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</p>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-gray-700 leading-relaxed min-h-[90px]">
                        {{ $laporan->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN --}}
    <div class="flex flex-col gap-6">

        {{-- Foto Kerusakan --}}
        <div class="card fade-in fade-in-3">
            <div class="card-header">
                <span class="card-title">
                    <i class="fa-solid fa-image text-emerald-500 mr-2"></i>Foto Kerusakan
                </span>
            </div>
            <div class="p-4">
                @php
                    $semuaFoto = [
                        'Atas'     => $laporan->foto_atas,
                        'Bawah'    => $laporan->foto_bawah,
                        'Kanan'    => $laporan->foto_kanan,
                        'Kiri'     => $laporan->foto_kiri,
                        'Depan'    => $laporan->foto_depan,
                        'Belakang' => $laporan->foto_belakang,
                    ];
                    $adaFoto = collect($semuaFoto)->filter()->isNotEmpty();
                @endphp

                @if($adaFoto)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($semuaFoto as $sisi => $path)
                            @if($path)
                                <div class="flex flex-col gap-1">
                                    <img src="{{ asset('storage/' . $path) }}"
                                         alt="Foto {{ $sisi }}"
                                         onclick="openPhoto('{{ asset('storage/' . $path) }}')"
                                         class="w-full rounded-lg object-cover h-24 cursor-zoom-in hover:opacity-90 transition border border-slate-100">
                                    <span class="text-xs text-center text-slate-400">{{ $sisi }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <p class="text-xs text-center text-slate-400 mt-3">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Klik foto untuk memperbesar
                    </p>
                @else
                    <div class="w-full h-40 bg-slate-100 rounded-xl flex flex-col items-center justify-center text-slate-400 gap-2">
                        <i class="fa-solid fa-image text-3xl"></i>
                        <span class="text-sm">Tidak ada foto</span>
                    </div>
                @endif

                {{-- Foto Kode Barang --}}
                @if($laporan->foto_kode)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Kode Barang</p>
                    <img src="{{ asset('storage/' . $laporan->foto_kode) }}"
                         alt="Foto Kode Barang"
                         onclick="openPhoto('{{ asset('storage/' . $laporan->foto_kode) }}')"
                         class="w-full rounded-lg object-cover h-24 cursor-zoom-in hover:opacity-90 transition border border-slate-100">
                </div>
                @endif
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card fade-in fade-in-4">
            <div class="card-header">
                <span class="card-title">
                    <i class="fa-solid fa-pen-to-square text-emerald-500 mr-2"></i>Update Status
                </span>
            </div>
            <div class="p-5">

                @if(strtolower($laporan->status ?? '') === 'selesai')
                    {{-- Sudah selesai: status dikunci, diatur dari Manajemen Perbaikan --}}
                    <div class="px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <span>Laporan ini sudah selesai diperbaiki. Status diatur dari menu <strong>Manajemen Perbaikan</strong>.</span>
                    </div>
                @else
                    <form action="{{ route('admin.laporan-masuk.update', $laporan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">
                                Status Laporan
                            </label>
                            <select name="status"
                                    class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                                <option value="menunggu"     {{ ($laporan->status ?? '') === 'menunggu'     ? 'selected' : '' }}>Menunggu</option>
                                <option value="diverifikasi" {{ ($laporan->status ?? '') === 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                <option value="diproses"     {{ ($laporan->status ?? '') === 'diproses'     ? 'selected' : '' }}>Diproses</option>
                                <option value="ditolak"      {{ ($laporan->status ?? '') === 'ditolak'      ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <p class="text-xs text-slate-400 mt-2">
                                Status "Selesai" otomatis terisi saat perbaikan ditandai selesai di Manajemen Perbaikan.
                            </p>
                        </div>
                        <button type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </form>
                @endif

                {{-- Tombol Verifikasi — hanya jika masih menunggu --}}
                @if(strtolower($laporan->status ?? '') === 'menunggu')
                <form action="{{ route('admin.laporan-masuk.verifikasi', $laporan->id) }}" method="POST" class="mt-3">
                    @csrf @method('PATCH')
                    <p class="text-xs text-slate-400 mb-3">
                        Skor prioritas akan dihitung otomatis oleh sistem
                    </p>
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-clipboard-check"></i> Verifikasi & Hitung Skor
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Modal Foto --}}
<div id="photoModal" onclick="closePhoto()"
     class="fixed inset-0 bg-black/70 z-[200] hidden items-center justify-center p-6">
    <img id="photoModalImg" src="" class="max-w-3xl max-h-[85vh] rounded-xl shadow-2xl object-contain">
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