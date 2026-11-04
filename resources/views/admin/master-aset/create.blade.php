@extends('layouts.admin')

@section('title', 'Tambah Aset — LAPORLAB Admin')
@section('page-title', 'Tambah Aset')
@section('page-subtitle', 'Tambahkan aset baru ke Master Data')

@section('content')

<div class="card fade-in fade-in-1 max-w-3xl">
    <div class="card-header">
        <div>
            <p class="card-title">Form Aset Baru</p>
            <p class="text-xs text-gray-400 mt-0.5">Field bertanda <span class="text-red-500">*</span> wajib diisi</p>
        </div>
    </div>
    <div class="card-body p-6">
        <form method="POST" action="{{ route('admin.master-aset.store') }}">
            @csrf
            @include('admin.master-aset._form')

            <div class="flex gap-3 pt-6">
                <a href="{{ route('admin.master-aset.index') }}"
                   class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition text-center">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition">
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>

@endsection