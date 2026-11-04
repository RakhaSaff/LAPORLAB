@extends('layouts.admin')

@section('title', 'Hasil Label — LAPORLAB Saintek')
@section('page-title', 'Hasil Generate Label')
@section('page-subtitle', 'Label aset berhasil digenerate')

@section('content')
<div class="fade-in fade-in-1">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.label-aset.index') }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-200 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fa-solid fa-arrow-left text-gray-500 text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Hasil Label Aset</h1>
                <p class="text-sm text-gray-500">{{ count($labels) }} label berhasil digenerate • Tidak dapat diubah</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fa-solid fa-print text-gray-400"></i>
                Print
            </button>
            <a href="{{ route('admin.label-aset.download', $labelAset->id) }}"
               class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">
                <i class="fa-solid fa-download"></i>
                Download PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Label List --}}
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Daftar Label Generated</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $labelAset->jenis_barang }} • {{ $labelAset->lokasi ?? 'Lokasi tidak ditentukan' }}</p>
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ count($labels) }} Label
                    </span>
                </div>
                <div class="p-6">

                    {{-- Success Alert --}}
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-5 flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-check text-emerald-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800">Label berhasil digenerate!</p>
                            <p class="text-xs text-emerald-600">Nomor urut {{ $labelAset->nomor_urut_awal }} — {{ $labelAset->nomor_urut_akhir }} • {{ $labelAset->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    {{-- Label Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3" id="label-grid">
                        @foreach($labels as $label)
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-3 text-center hover:border-emerald-300 hover:shadow-sm transition-all group">
                            {{-- QR placeholder --}}
                            <div class="w-12 h-12 bg-gray-100 rounded-lg mx-auto mb-2 flex items-center justify-center">
                                <i class="fa-solid fa-qrcode text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-xs font-bold font-mono text-gray-800 tracking-wider break-all">{{ $label }}</p>
                            <div class="mt-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="copyLabel('{{ $label }}')"
                                        class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                                    <i class="fa-solid fa-copy mr-1"></i>Salin
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Lock Notice --}}
                    <div class="mt-5 bg-yellow-50 border border-yellow-200 rounded-xl p-3 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-yellow-600 text-sm shrink-0"></i>
                        <p class="text-xs text-yellow-700 font-medium">Label di atas sudah tersimpan permanen dan tidak dapat diubah.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Aset --}}
        <div class="space-y-4">

            {{-- Foto Barang --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Foto Barang</h3>
                </div>
                <div class="p-4">
                    <img src="{{ asset('storage/' . $fotoPath) }}" alt="Foto Barang"
                         class="w-full rounded-xl object-cover max-h-56 border border-gray-100">
                </div>
            </div>

            {{-- Info Label --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Info Label</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jenis Barang</span>
                        <span class="font-semibold text-gray-800">{{ $labelAset->jenis_barang }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kode</span>
                        <span class="font-bold font-mono text-emerald-700">{{ $labelAset->kode_barang }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tahun</span>
                        <span class="font-semibold text-gray-800">{{ $labelAset->tahun }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Lokasi</span>
                        <span class="font-semibold text-gray-800 text-right max-w-32">{{ $labelAset->lokasi ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Jumlah</span>
                        <span class="font-semibold text-gray-800">{{ $labelAset->jumlah }} unit</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Nomor Urut</span>
                        <span class="font-semibold text-gray-800">{{ str_pad($labelAset->nomor_urut_awal, 3, '0', STR_PAD_LEFT) }} — {{ str_pad($labelAset->nomor_urut_akhir, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Dibuat</span>
                        <span class="font-semibold text-gray-800">{{ $labelAset->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Copy All --}}
            <button onclick="copyAllLabels()"
                    class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition-all text-sm flex items-center justify-center gap-2 shadow-sm">
                <i class="fa-solid fa-copy text-gray-400"></i>
                Salin Semua Label
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .card-header button, a[href], button:not(#label-grid *) { display: none !important; }
    .main-content { margin-left: 0 !important; }
    #label-grid { grid-template-columns: repeat(3, 1fr); }
    .card { box-shadow: none; border: 1px solid #e5e7eb; }
}
</style>
@endpush

@push('scripts')
<script>
function copyLabel(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Label disalin: ' + text);
    });
}

function copyAllLabels() {
    const labels = @json($labels);
    navigator.clipboard.writeText(labels.join('\n')).then(() => {
        showToast('Semua ' + labels.length + ' label berhasil disalin!');
    });
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-6 right-6 bg-gray-900 text-white text-sm font-medium px-4 py-3 rounded-xl shadow-lg z-50 transition-all';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush
@endsection
