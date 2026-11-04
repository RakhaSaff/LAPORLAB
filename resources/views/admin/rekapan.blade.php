@extends('layouts.admin')

@section('title', 'Rekapan Perbaikan — LAPORLAB Admin')
@section('page-title', 'Rekapan Perbaikan')
@section('page-subtitle', 'Laporan fasilitas yang telah selesai diperbaiki')

@push('styles')
<style>
@media print {
    .no-print { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .sidebar { display: none !important; }
    .topbar { display: none !important; }
    .print-area { padding: 0 !important; }
    body { background: white !important; }
}
</style>
@endpush

@section('content')

{{-- Filter & Actions --}}
<div class="card mb-6 fade-in fade-in-1 no-print">
    <div class="p-5 flex flex-wrap gap-3 items-center justify-between">
        <form method="GET" action="{{ route('admin.rekapan') }}" class="flex flex-wrap gap-3 items-center">
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                <i class="fa-solid fa-calendar text-gray-400 text-xs"></i>
                <select name="bulan" class="text-sm text-gray-600 bg-transparent focus:outline-none cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse(date('Y') . '-' . $b . '-01')->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                <i class="fa-solid fa-calendar-days text-gray-400 text-xs"></i>
                <select name="tahun" class="text-sm text-gray-600 bg-transparent focus:outline-none cursor-pointer">
                    @foreach(range(date('Y'), date('Y')-3) as $t)
                        <option value="{{ $t }}" {{ request('tahun', date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request('bulan') || request('tahun'))
            <a href="{{ route('admin.rekapan') }}"
               class="inline-flex items-center gap-1 px-4 py-2 border border-gray-200 text-gray-500 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
            @endif
        </form>
        <div class="flex gap-2">
            <button onclick="printSemua()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-print"></i> Print Semua
            </button>
            <form method="POST" action="{{ route('admin.rekapan.hapus-otomatis') }}"
                  onsubmit="return confirm('Hapus semua rekapan yang sudah lebih dari 3 bulan?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-semibold transition border border-red-200">
                    <i class="fa-solid fa-clock-rotate-left"></i> Hapus > 3 Bulan
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 fade-in fade-in-1 no-print">
    <div class="stat-card green">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $totalRekapan }}</p>
                <p class="text-xs text-gray-500">Total Rekapan</p>
            </div>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $rekapanBulanIni }}</p>
                <p class="text-xs text-gray-500">Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $akanHapus }}</p>
                <p class="text-xs text-gray-500">Akan Dihapus (>3 bln)</p>
            </div>
        </div>
    </div>
</div>

{{-- Print Header --}}
<div class="hidden print:block mb-6" id="print-header">
    <div style="text-align:center;border-bottom:2px solid #059669;padding-bottom:12px;margin-bottom:16px;">
        <h2 style="font-size:18px;font-weight:800;color:#0a4a2e;">REKAPAN PERBAIKAN FASILITAS</h2>
        <p style="font-size:13px;color:#6b7280;">LAPORLAB Saintek — FST UIN Sunan Ampel Surabaya</p>
        <p style="font-size:12px;color:#9ca3af;">
            {{ request('bulan') ? \Carbon\Carbon::parse(date('Y') . '-' . request('bulan') . '-01')->translatedFormat('F') : 'Semua Bulan' }}
            {{ request('tahun', date('Y')) }}
            &nbsp;|&nbsp; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}
        </p>
    </div>
</div>

{{-- Table --}}
<div class="card fade-in fade-in-2" id="print-area">
    <div class="card-header no-print">
        <div>
            <p class="card-title">Daftar Rekapan Perbaikan</p>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ request('bulan') ? \Carbon\Carbon::parse(date('Y') . '-' . request('bulan') . '-01')->translatedFormat('F') . ' ' . request('tahun', date('Y')) : 'Semua periode' }}
            </p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Tingkat</th>
                    <th>Selesai Pada</th>
                    <th class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $laporan)
                <tr id="row-{{ $laporan->id }}">
                    <td class="text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>
                    <td>
                        <div class="font-semibold text-sm text-gray-800">{{ $laporan->user->name ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $laporan->user->email ?? '' }}</div>
                    </td>
                    <td class="font-medium text-gray-800">{{ $laporan->nama_barang }}</td>
                    <td class="text-sm text-gray-600">{{ ucfirst($laporan->jenis_barang) }}</td>
                    <td class="text-sm text-gray-600 max-w-[140px]">
                        <p class="line-clamp-2">{{ $laporan->tempat_label ?? $laporan->tempat_utama }}</p>
                    </td>
                    <td class="text-sm text-gray-600 max-w-[160px]">
                        <p class="line-clamp-2">{{ $laporan->deskripsi }}</p>
                    </td>
                    <td>
                        <div class="flex gap-1 flex-wrap">
                            @foreach(['atas','bawah','kanan','kiri','depan','belakang'] as $sisi)
                                @if($laporan->{'foto_'.$sisi})
                                <img src="{{ asset('storage/' . $laporan->{'foto_'.$sisi}) }}"
                                     class="w-8 h-8 object-cover rounded border cursor-pointer hover:opacity-80 transition no-print"
                                     onclick="bukaFoto('{{ asset('storage/' . $laporan->{'foto_'.$sisi}) }}')"
                                     title="{{ ucfirst($sisi) }}">
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td>
                        @if($laporan->tingkat_kerusakan === 'tinggi')
                            <span class="badge badge-tinggi">Tinggi</span>
                        @elseif($laporan->tingkat_kerusakan === 'sedang')
                            <span class="badge badge-sedang">Sedang</span>
                        @else
                            <span class="badge badge-rendah">Rendah</span>
                        @endif
                    </td>
                    <td class="text-xs text-gray-500">
                        <div>{{ \Carbon\Carbon::parse($laporan->updated_at)->format('d M Y') }}</div>
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($laporan->updated_at)->format('H:i') }}</div>
                    </td>
                    <td class="no-print">
                        <div class="flex gap-2">
                            <button onclick="printBaris({{ $laporan->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.rekapan.hapus', $laporan->id) }}"
                                  onsubmit="return confirm('Hapus rekapan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-14 text-gray-400">
                        <i class="fa-solid fa-box-archive text-4xl mb-3 block text-gray-200"></i>
                        <p class="font-medium">Belum ada rekapan perbaikan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($laporans) && $laporans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 no-print">
        {{ $laporans->links() }}
    </div>
    @endif
</div>

{{-- Modal Foto --}}
<div id="fotoModal" onclick="tutupFoto()"
     class="fixed inset-0 bg-black/70 z-[200] hidden items-center justify-center p-6 no-print">
    <img id="fotoModalImg" src="" class="max-w-2xl max-h-[80vh] rounded-xl shadow-2xl object-contain">
</div>

@endsection

@push('scripts')
<script>
function bukaFoto(src) {
    document.getElementById('fotoModalImg').src = src;
    document.getElementById('fotoModal').classList.remove('hidden');
    document.getElementById('fotoModal').classList.add('flex');
}
function tutupFoto() {
    document.getElementById('fotoModal').classList.add('hidden');
    document.getElementById('fotoModal').classList.remove('flex');
}
function printSemua() {
    document.getElementById('print-header').classList.remove('hidden');
    window.print();
    document.getElementById('print-header').classList.add('hidden');
}
function printBaris(id) {
    const row = document.getElementById('row-' + id);
    if (!row) return;
    const win = window.open('', '_blank', 'width=900,height=600');
    win.document.write(`
        <html><head>
        <title>Rekapan Perbaikan</title>
        <style>
            body { font-family: sans-serif; padding: 24px; }
            h2 { color: #0a4a2e; border-bottom: 2px solid #059669; padding-bottom: 8px; }
            table { width: 100%; border-collapse: collapse; margin-top: 16px; }
            th, td { padding: 10px 12px; border: 1px solid #e5e7eb; font-size: 13px; text-align: left; }
            th { background: #f8fafc; font-weight: 700; color: #374151; }
            img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin: 2px; }
            .meta { color: #6b7280; font-size: 12px; margin-bottom: 16px; }
        </style>
        </head><body>
        <h2>Rekapan Perbaikan Fasilitas</h2>
        <p class="meta">LAPORLAB Saintek · FST UIN Sunan Ampel Surabaya · Dicetak: ${new Date().toLocaleString('id-ID')}</p>
        <table>
            <tr><th>Pelapor</th><td>${row.cells[1].innerText}</td></tr>
            <tr><th>Nama Barang</th><td>${row.cells[2].innerText}</td></tr>
            <tr><th>Jenis Barang</th><td>${row.cells[3].innerText}</td></tr>
            <tr><th>Lokasi</th><td>${row.cells[4].innerText}</td></tr>
            <tr><th>Deskripsi</th><td>${row.cells[5].innerText}</td></tr>
            <tr><th>Foto</th><td>${row.cells[6].innerHTML}</td></tr>
            <tr><th>Tingkat Kerusakan</th><td>${row.cells[7].innerText}</td></tr>
            <tr><th>Selesai Pada</th><td>${row.cells[8].innerText}</td></tr>
        </table>
        </body></html>
    `);
    win.document.close();
    win.focus();
    setTimeout(function() {
        win.print();
    }, 500);
}
</script>
@endpush