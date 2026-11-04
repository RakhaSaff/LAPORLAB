@extends('layouts.admin')

@section('title', 'Import Master Data — LAPORLAB Admin')
@section('page-title', 'Import Master Data')
@section('page-subtitle', 'Import data aset dari file Excel atau CSV')

@section('content')

@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium flex items-center gap-2">
    <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="card fade-in fade-in-1">
        <div class="card-header">
            <p class="card-title">Upload File</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.import-master-aset.preview') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">File Excel/CSV</label>
                    <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
                    @error('file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
                    <i class="fa-solid fa-eye"></i> Preview & Lanjutkan
                </button>
            </form>
        </div>
    </div>

    <div class="card fade-in fade-in-2">
        <div class="card-header">
            <p class="card-title">Kolom yang Dikenali</p>
        </div>
        <div class="p-6 text-sm text-gray-600 space-y-3">
            <div>
                <p class="font-semibold text-gray-800 mb-1">Kode Barang</p>
                <p class="text-xs text-gray-400">kode_barang, id_barang, kode_aset, no_inv, nomor_referensi, ref, dll</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Nama Barang</p>
                <p class="text-xs text-gray-400">nama_barang, nama, barang, fasilitas, dll</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Kategori</p>
                <p class="text-xs text-gray-400">jenis_barang, kategori, category, tipe, dll</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Lokasi</p>
                <p class="text-xs text-gray-400">tempat_utama, lokasi, ruang, gedung (digabung dengan sub_lokasi kalau ada)</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Spesifikasi</p>
                <p class="text-xs text-gray-400">deskripsi, keterangan, catatan, spesifikasi, dll</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">Tahun Perolehan</p>
                <p class="text-xs text-gray-400">tahun_perolehan, tahun, year (harus 4 digit angka)</p>
            </div>
            <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mt-4">
                <i class="fa-solid fa-circle-info"></i>
                Kolom seperti <code>status</code>, <code>tingkat_kerusakan</code>, atau <code>kegiatan_akademik</code> (kalau ada di file kamu) otomatis diabaikan, karena itu konsep khusus Laporan, bukan Master Data.
            </p>
        </div>
    </div>

</div>

@endsection