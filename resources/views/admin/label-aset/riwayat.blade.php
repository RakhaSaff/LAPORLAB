@extends('layouts.admin')

@section('title', 'Riwayat Label Aset — LAPORLAB Saintek')
@section('page-title', 'Riwayat Label Aset')
@section('page-subtitle', 'Semua label yang pernah digenerate')

@section('content')
<div class="fade-in fade-in-1">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.label-aset.index') }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left text-gray-500 text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Label Aset</h1>
                <p class="text-sm text-gray-500">Total {{ $riwayat->total() }} entri label</p>
            </div>
        </div>
        <a href="{{ route('admin.label-aset.index') }}"
           class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">
            <i class="fa-solid fa-plus"></i>
            Generate Baru
        </a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Foto</th>
                        <th>Jenis Barang</th>
                        <th>Lokasi</th>
                        <th>Jumlah Label</th>
                        <th>Range Nomor</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $r)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ $riwayat->firstItem() + $loop->index }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $r->foto_path) }}" alt=""
                                 class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                        </td>
                        <td>
                            <p class="font-semibold text-gray-800">{{ $r->jenis_barang }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $r->kode_barang }}</p>
                        </td>
                        <td class="text-gray-600">{{ $r->lokasi ?? '-' }}</td>
                        <td>
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-full">
                                {{ $r->jumlah }} label
                            </span>
                        </td>
                        <td class="font-mono text-xs text-gray-600">
                            {{ str_pad($r->nomor_urut_awal, 3, '0', STR_PAD_LEFT) }} — {{ str_pad($r->nomor_urut_akhir, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="text-xs text-gray-500">{{ $r->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.label-aset.detail', $r->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition-all">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-400">
                            <i class="fa-solid fa-tag text-3xl mb-3 block opacity-30"></i>
                            Belum ada label yang digenerate
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
