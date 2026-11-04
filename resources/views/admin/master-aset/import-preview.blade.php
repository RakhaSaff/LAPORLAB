@extends('layouts.admin')

@section('title', 'Preview Import Master Data — LAPORLAB Admin')
@section('page-title', 'Preview Import Master Data')
@section('page-subtitle', 'Periksa hasil mapping kolom sebelum data disimpan')

@section('content')

<div class="flex items-center justify-end mb-4">
    <a href="{{ route('admin.import-master-aset.cancel') }}"
       class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
        <i class="fa-solid fa-xmark"></i> Batal
    </a>
</div>

{{-- Summary bar --}}
<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card blue">
        <p class="text-2xl font-extrabold text-gray-900">{{ $total }}</p>
        <p class="text-xs text-gray-500">Total Baris</p>
    </div>
    <div class="stat-card blue">
        <p class="text-2xl font-extrabold text-gray-900">{{ strtoupper($extension) }}</p>
        <p class="text-xs text-gray-500">Format File</p>
    </div>
    <div class="stat-card green">
        <p class="text-2xl font-extrabold text-gray-900">{{ count($kolom['terdeteksi']) }}</p>
        <p class="text-xs text-gray-500">Kolom Terdeteksi</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Mapping kolom --}}
    <div class="card fade-in fade-in-1 lg:col-span-1">
        <div class="card-header">
            <p class="card-title">Mapping Kolom</p>
        </div>
        <div class="p-5">
            @if(count($kolom['terdeteksi']) > 0)
            <p class="text-xs font-semibold text-emerald-700 mb-2"><i class="fa-solid fa-circle-check"></i> Terdeteksi otomatis</p>
            <div class="space-y-1.5 mb-4">
                @foreach($kolom['terdeteksi'] as $asal => $tujuan)
                <div class="flex items-center gap-1.5 text-xs bg-gray-50 rounded-lg px-2.5 py-1.5">
                    <span class="font-mono text-gray-600">{{ $asal }}</span>
                    <i class="fa-solid fa-arrow-right text-gray-300"></i>
                    <span class="font-mono text-emerald-700">{{ $tujuan }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if(count($kolom['tidakDikenal']) > 0)
            <p class="text-xs font-semibold text-amber-700 mb-2"><i class="fa-solid fa-triangle-exclamation"></i> Tidak dikenal (diabaikan)</p>
            <div class="space-y-1.5">
                @foreach($kolom['tidakDikenal'] as $col)
                <div class="text-xs bg-gray-50 text-gray-400 rounded-lg px-2.5 py-1.5 font-mono">{{ $col }}</div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Preview data --}}
    <div class="card fade-in fade-in-2 lg:col-span-3">
        <div class="card-header">
            <p class="card-title">Preview Data ({{ count($preview) }} baris)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis Aset</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($preview as $i => $row)
                    <tr>
                        <td class="text-gray-400 text-xs font-mono">{{ $i + 1 }}</td>
                        <td class="text-sm font-mono text-gray-700">{{ $row['kode_barang'] ?? '-' }}</td>
                        <td class="font-medium text-gray-800">{{ $row['nama_barang'] ?? '-' }}</td>
                        <td class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $row['jenis_aset'] ?? '-')) }}</td>
                        <td class="text-sm text-gray-600">{{ $row['kategori'] ?? '-' }}</td>
                        <td class="text-sm text-gray-600">{{ $row['lokasi'] ?? '-' }}</td>
                        <td class="text-sm text-gray-600">{{ $row['tahun_perolehan'] ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-10 text-gray-400">Tidak ada data untuk ditampilkan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="flex justify-between items-center mt-6">
    <a href="{{ route('admin.import-master-aset.cancel') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
        <i class="fa-solid fa-arrow-left"></i> Kembali & Upload Ulang
    </a>
    <form method="POST" action="{{ route('admin.import-master-aset.store') }}">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
            <i class="fa-solid fa-database"></i> Import {{ $total }} Aset ke Master Data
        </button>
    </form>
</div>

@endsection