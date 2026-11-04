@extends('layouts.admin')

@section('title', 'Label Aset — LAPORLAB Saintek')
@section('page-title', 'Label Aset')
@section('page-subtitle', 'Generate label otomatis untuk aset fasilitas')

@section('content')
<div class="fade-in fade-in-1">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Label Aset</h1>
            <p class="text-sm text-gray-500 mt-1">Upload foto barang, pilih jenis, tentukan jumlah — label otomatis digenerate</p>
        </div>
        <a href="{{ route('admin.label-aset.riwayat') }}"
           class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
            <i class="fa-solid fa-clock-rotate-left text-gray-400"></i>
            Riwayat Label
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Generate Label --}}
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Generate Label Baru</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.label-aset.generate') }}" method="POST" enctype="multipart/form-data" id="formLabel">
                        @csrf

                        {{-- Upload Foto --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Foto Barang <span class="text-red-500">*</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition-all duration-200"
                                 id="dropzone" onclick="document.getElementById('foto').click()">
                                <div id="preview-container" class="hidden">
                                    <img id="foto-preview" src="" alt="Preview" class="max-h-48 mx-auto rounded-lg shadow-sm object-contain">
                                    <p class="text-xs text-gray-400 mt-2" id="foto-name"></p>
                                </div>
                                <div id="upload-placeholder">
                                    <i class="fa-solid fa-camera text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-sm font-medium text-gray-600">Klik untuk upload foto barang</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP • Maks 5MB</p>
                                </div>
                            </div>
                            <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                            @error('foto')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jenis Barang --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Barang <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_barang" id="jenis_barang"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                    onchange="updatePreviewKode()">
                                <option value="">-- Pilih Jenis Barang --</option>
                                @foreach($daftarBarang as $nama => $kode)
                                    <option value="{{ $nama }}" data-kode="{{ $kode }}" {{ old('jenis_barang') == $nama ? 'selected' : '' }}>
                                        {{ $nama }} ({{ $kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_barang')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Lokasi <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                                   placeholder="Contoh: Lab SI - Lantai 3"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                        </div>

                        {{-- Jumlah --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah Barang yang Dilabeli <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="ubahJumlah(-1)"
                                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-lg flex items-center justify-center transition-all">−</button>
                                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', 1) }}"
                                       min="1" max="500" oninput="updatePreviewKode()"
                                       class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm text-center font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                                <button type="button" onclick="ubahJumlah(1)"
                                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-lg flex items-center justify-center transition-all">+</button>
                            </div>
                            @error('jumlah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preview Kode --}}
                        <div id="preview-kode-box" class="hidden mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                            <p class="text-xs font-semibold text-emerald-700 mb-2">Preview Label yang akan digenerate:</p>
                            <div id="preview-kode-list" class="flex flex-wrap gap-2"></div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" id="btn-generate"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-solid fa-tag"></i>
                            Eksekusi — Generate Label
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Info + Riwayat Terakhir --}}
        <div class="space-y-4">

            {{-- Format Label --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Format Label</h3>
                </div>
                <div class="p-4">
                    <div class="bg-gray-50 rounded-xl p-4 text-center mb-4">
                        <p class="text-xs text-gray-500 mb-1">Contoh Label</p>
                        <p class="text-lg font-bold text-emerald-700 font-mono tracking-wider">SMTFAS-PC-2026-001</p>
                    </div>
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="flex items-center gap-2">
                            <span class="bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded font-mono">SMTFAS</span>
                            <span>Prefix sistem</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded font-mono">PC</span>
                            <span>Kode jenis barang</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-orange-100 text-orange-700 font-bold px-2 py-0.5 rounded font-mono">2026</span>
                            <span>Tahun registrasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="bg-purple-100 text-purple-700 font-bold px-2 py-0.5 rounded font-mono">001</span>
                            <span>Nomor urut (auto)</span>
                        </div>
                    </div>
                    <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-xs text-yellow-700 font-semibold">
                            <i class="fa-solid fa-lock mr-1"></i>
                            Label yang sudah digenerate tidak dapat diubah
                        </p>
                    </div>
                </div>
            </div>

            {{-- Riwayat Terakhir --}}
            @if($riwayat->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Terakhir</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($riwayat as $r)
                    <a href="{{ route('admin.label-aset.detail', $r->id) }}"
                       class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors">
                        <img src="{{ asset('storage/' . $r->foto_path) }}" alt=""
                             class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $r->jenis_barang }}</p>
                            <p class="text-xs text-gray-400">{{ $r->jumlah }} label • {{ $r->created_at->diffForHumans() }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('foto-preview').src = e.target.result;
            document.getElementById('foto-name').textContent = input.files[0].name;
            document.getElementById('preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function ubahJumlah(delta) {
    const input = document.getElementById('jumlah');
    const val = parseInt(input.value) + delta;
    if (val >= 1 && val <= 500) {
        input.value = val;
        updatePreviewKode();
    }
}

function updatePreviewKode() {
    const select = document.getElementById('jenis_barang');
    const jumlah = parseInt(document.getElementById('jumlah').value) || 0;
    const box    = document.getElementById('preview-kode-box');
    const list   = document.getElementById('preview-kode-list');

    if (!select.value || jumlah < 1) {
        box.classList.add('hidden');
        return;
    }

    const kode  = select.options[select.selectedIndex].dataset.kode;
    const tahun = new Date().getFullYear();
    const show  = Math.min(jumlah, 5);

    box.classList.remove('hidden');
    list.innerHTML = '';

    for (let i = 1; i <= show; i++) {
        const num = String(i).padStart(3, '0');
        const el  = document.createElement('span');
        el.className = 'bg-white border border-emerald-300 text-emerald-800 text-xs font-bold font-mono px-2 py-1 rounded-lg';
        el.textContent = `SMTFAS-${kode}-${tahun}-${num}`;
        list.appendChild(el);
    }

    if (jumlah > 5) {
        const el = document.createElement('span');
        el.className = 'bg-gray-100 text-gray-500 text-xs font-medium px-2 py-1 rounded-lg';
        el.textContent = `+${jumlah - 5} lainnya...`;
        list.appendChild(el);
    }
}
</script>
@endpush
@endsection
