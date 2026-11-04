@extends('layouts.admin')

@section('title', 'Profil Saya — LAPORLAB Admin')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun admin')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

<div class="max-w-2xl mx-auto">
    <div class="card fade-in fade-in-1">
        <div class="card-header">
            <span class="card-title">
                <i class="fa-solid fa-user text-emerald-500 mr-2"></i>Informasi Akun
            </span>
        </div>
        <div class="p-6">

            {{-- Avatar --}}
            <div class="flex items-center gap-4 mb-8">
                <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-2xl">
                    {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="text-lg font-bold text-gray-900">{{ $admin->name }}</div>
                    <div class="text-sm text-gray-400">{{ $admin->email }}</div>
                    <div class="text-xs text-emerald-600 font-medium mt-0.5">{{ $admin->jabatan ?? 'Administrator' }}</div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $admin->jabatan) }}"
                               placeholder="Contoh: Kepala Prodi, Kabag, dll"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('email') border-red-400 @enderror">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-slate-100 pt-5">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Ganti Password <span class="normal-case font-normal text-slate-400">(kosongkan jika tidak ingin mengganti)</span></p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Password Baru</label>
                                <input type="password" name="password"
                                       class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('password') border-red-400 @enderror">
                                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection