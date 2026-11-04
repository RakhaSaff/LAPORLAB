@extends('layouts.admin')

@section('title', 'Master Data Aset — LAPORLAB Admin')
@section('page-title', 'Master Data Aset')
@section('page-subtitle', 'Katalog resmi seluruh aset/fasilitas Fakultas Saintek')

@section('content')

{{-- Summary mini cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card blue fade-in fade-in-1">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $totalAset }}</p>
                <p class="text-xs text-gray-500">Total Aset Terdaftar</p>
            </div>
        </div>
    </div>
    <div class="stat-card green fade-in fade-in-2">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-building"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $totalBarangTetap }}</p>
                <p class="text-xs text-gray-500">Barang Tetap</p>
            </div>
        </div>
    </div>
    <div class="stat-card orange fade-in fade-in-3">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="fa-solid fa-box"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $totalBarangTidakTetap }}</p>
                <p class="text-xs text-gray-500">Barang Tidak Tetap</p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

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
            </ul>
        </details>
        @endif
    </div>
    @else
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        Import selesai: <strong>{{ $ir['berhasil'] }}</strong> aset berhasil ditambahkan ke Master Data.
    </div>
    @endif
@endif

{{-- Filter, Search & Tambah --}}
<div class="card mb-6 fade-in fade-in-1">
    <div class="p-5 flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <select name="jenis_aset" onchange="this.form.submit()"
                    class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:outline-none focus:border-emerald-400 bg-white">
                <option value="">Semua Jenis</option>
                <option value="barang_tetap" {{ request('jenis_aset') == 'barang_tetap' ? 'selected' : '' }}>Barang Tetap</option>
                <option value="barang_tidak_tetap" {{ request('jenis_aset') == 'barang_tidak_tetap' ? 'selected' : '' }}>Barang Tidak Tetap</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama alat/no. inventarisasi/merk..."
                   class="text-sm border border-gray-200 rounded-lg px-3 py-2 w-56 focus:outline-none focus:border-emerald-400">
            <button type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.import-master-aset.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                <i class="fa-solid fa-file-import"></i> Import CSV/Excel
            </a>
            <a href="{{ route('admin.master-aset.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-plus"></i> Tambah Aset
            </a>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card fade-in fade-in-2">
    <div class="card-header">
        <div>
            <p class="card-title">Daftar Aset</p>
            <p class="text-xs text-gray-400 mt-0.5">Total {{ $asets->total() ?? 0 }} aset terdaftar</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Alat</th>
                    <th>Merk</th>
                    <th>No. Inventarisasi</th>
                    <th>Jumlah</th>
                    <th>Keadaan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asets ?? [] as $item)
                <tr>
                    <td class="text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>
                    <td class="font-medium text-gray-800">{{ $item->nama_barang }}</td>
                    <td class="text-sm text-gray-600">{{ $item->merk ?? '-' }}</td>
                    <td class="text-sm text-gray-700 font-mono">{{ $item->kode_barang ?? '-' }}</td>
                    <td class="text-sm text-gray-600">{{ $item->jumlah ?? '-' }}</td>
                    <td>
                        @if(strtolower($item->keadaan ?? '') === 'baik')
                            <span class="badge badge-rendah">{{ $item->keadaan }}</span>
                        @elseif($item->keadaan)
                            <span class="badge badge-tinggi">{{ $item->keadaan }}</span>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="text-sm text-gray-600">{{ $item->keterangan ?? '-' }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.master-aset.edit', $item->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.master-aset.destroy', $item->id) }}"
                                  onsubmit="return confirm('Yakin mau hapus aset &quot;{{ addslashes($item->nama_barang) }}&quot; dari Master Data? Aksi ini tidak bisa dibatalkan.');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-14 text-gray-400">
                        <i class="fa-solid fa-boxes-stacked text-4xl mb-3 block text-gray-200"></i>
                        <p class="font-medium">Belum ada aset terdaftar di Master Data</p>
                        <a href="{{ route('admin.master-aset.create') }}" class="text-emerald-600 text-sm font-semibold hover:underline mt-2 inline-block">
                            + Tambah aset pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($asets) && $asets->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $asets->links() }}
    </div>
    @endif
</div>

@endsection