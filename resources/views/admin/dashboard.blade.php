@extends('layouts.admin')

@section('title', 'Dashboard — LAPORLAB Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan laporan kerusakan fasilitas')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">

    <a href="{{ route('admin.laporan-masuk.index') }}" class="stat-card green fade-in fade-in-1 block hover:opacity-90 transition">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Laporan</p>
                <p class="text-3xl font-extrabold text-gray-900" id="total-laporan">{{ $totalLaporan ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Semua laporan masuk</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-file-lines text-lg"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.perlu-verifikasi') }}" class="stat-card orange fade-in fade-in-2 block hover:opacity-90 transition">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Perlu Diverifikasi</p>
                <p class="text-3xl font-extrabold text-gray-900" id="perlu-verifikasi">{{ $perluVerifikasi ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Menunggu tindakan admin</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                <i class="fa-solid fa-clock text-lg"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.manajemen-perbaikan') }}" class="stat-card blue fade-in fade-in-3 block hover:opacity-90 transition">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Sudah Diverifikasi</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $sudahVerifikasi ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Laporan terverifikasi</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.rekapan') }}" class="stat-card red fade-in fade-in-4 block hover:opacity-90 transition">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Selesai Diperbaiki</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ $selesai ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Perbaikan tuntas</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center text-green-500">
                <i class="fa-solid fa-wrench text-lg"></i>
            </div>
        </div>
    </a>

</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">

    <div class="card lg:col-span-2 fade-in fade-in-2">
        <div class="card-header">
            <div>
                <p class="card-title">Grafik Laporan per Bulan</p>
                <p class="text-xs text-gray-400 mt-0.5">Verifikasi vs belum diverifikasi</p>
            </div>
            <form method="GET">
                <select name="tahun" onchange="this.form.submit()"
                        class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none focus:border-emerald-400">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
        <div class="p-5">
            <canvas id="chartBulanan" height="110"></canvas>
        </div>
    </div>

    <div class="card fade-in fade-in-3">
        <div class="card-header">
            <div>
                <p class="card-title">Dominan Kerusakan</p>
                <p class="text-xs text-gray-400 mt-0.5">Berdasarkan kategori laporan</p>
            </div>
        </div>
        <div class="p-5 flex flex-col items-center">
            <canvas id="chartDominan" height="180"></canvas>
            <div class="mt-4 w-full space-y-2" id="legend-dominan"></div>
        </div>
    </div>

</div>

{{-- Tabel Laporan Terbaru --}}
<div class="card fade-in fade-in-3">
    <div class="card-header">
        <div>
            <p class="card-title">Laporan Masuk Terbaru</p>
            <p class="text-xs text-gray-400 mt-0.5">Kelola & verifikasi tingkat kerusakan</p>
        </div>
        <a href="{{ route('admin.laporan-masuk.index') }}"
           class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
            Lihat Semua <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelapor</th>
                    <th>Fasilitas / Barang</th>
                    <th>Lokasi</th>
                    <th>Tingkat</th>
                    <th>Status</th>
                    <th>Waktu Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tbody-laporan">
                @forelse($laporanTerbaru ?? [] as $laporan)
                <tr>
                    <td class="text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>
                    <td>
                        <div class="font-semibold text-gray-800 text-sm">{{ $laporan->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $laporan->user->role ?? 'Mahasiswa' }}</div>
                    </td>
                    <td>
                        <div class="font-medium text-gray-800">{{ $laporan->nama_barang }}</div>
                        <div class="text-xs text-gray-400">{{ ucfirst($laporan->jenis_barang ?? '-') }}</div>
                    </td>
                    <td class="text-gray-600 text-sm">
                        {{ ucwords(str_replace('_', ' ', $laporan->tempat_label ?? $laporan->tempat_utama ?? '-')) }}
                    </td>
                    <td>
                        @if($laporan->tingkat_kerusakan === 'tinggi')
                            <span class="badge badge-tinggi"><i class="fa-solid fa-circle-exclamation"></i> Tinggi</span>
                        @elseif($laporan->tingkat_kerusakan === 'sedang')
                            <span class="badge badge-sedang"><i class="fa-solid fa-triangle-exclamation"></i> Sedang</span>
                        @else
                            <span class="badge badge-rendah"><i class="fa-solid fa-circle-info"></i> Rendah</span>
                        @endif
                    </td>
                    <td>
                        @if($laporan->status === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($laporan->status === 'diproses')
                            <span class="badge badge-diproses">Diproses</span>
                        @elseif($laporan->status === 'selesai')
                            <span class="badge badge-selesai">Selesai</span>
                        @else
                            <span class="badge badge-pending">Menunggu</span>
                        @endif
                    </td>
                    <td class="text-xs text-gray-500">
                        <div>{{ \Carbon\Carbon::parse($laporan->created_at)->diffForHumans() }}</div>
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y, H:i') }}</div>
                    </td>
                    <td>
                        <a href="{{ route('admin.laporan-masuk.show', $laporan->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr id="empty-row">
                    <td colspan="8" class="text-center py-10 text-gray-400">
                        <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                        Belum ada laporan masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Grafik Batang ──
const ctx1 = document.getElementById('chartBulanan').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
        datasets: [
            {
                label: 'Sudah Diverifikasi',
                data: {!! json_encode($chartVerifikasi ?? array_fill(0, 12, 0)) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.25)',
                borderColor: '#2563eb',
                borderWidth: 2.5,
                borderRadius: 6,
            },
            {
                label: 'Belum Diverifikasi',
                data: {!! json_encode($chartBelumVerifikasi ?? array_fill(0, 12, 0)) !!},
                backgroundColor: 'rgba(139, 92, 246, 0.25)',
                borderColor: '#7c3aed',
                borderWidth: 2.5,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: { family: 'Plus Jakarta Sans', size: 12 },
                    boxWidth: 12,
                    borderRadius: 4,
                    color: '#374151'
                }
            },
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: { font: { family: 'Plus Jakarta Sans' }, color: '#6b7280' }
            },
            x: {
                grid: { display: false },
                ticks: { font: { family: 'Plus Jakarta Sans' }, color: '#6b7280' }
            }
        }
    }
});

// ── Grafik Donut ──
const ctx2 = document.getElementById('chartDominan').getContext('2d');
const dominanLabels = {!! json_encode(!empty($dominanLabels) ? $dominanLabels : ['Belum ada data']) !!};
const dominanData   = {!! json_encode(!empty($dominanData) ? $dominanData : [1]) !!};
const colors = ['#2563eb', '#7c3aed', '#06b6d4', '#f59e0b', '#ec4899'];

new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: dominanLabels,
        datasets: [{
            data: dominanData,
            backgroundColor: colors,
            borderWidth: 0,
            hoverOffset: 6
        }]
    },
    options: {
        cutout: '68%',
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

const legend = document.getElementById('legend-dominan');
const total = dominanData.reduce((a, b) => a + b, 0);
dominanLabels.forEach((label, i) => {
    const pct = total > 0 ? ((dominanData[i] / total) * 100).toFixed(0) : 0;
    legend.innerHTML += `
        <div class="flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${colors[i]}"></span>
                <span class="text-gray-600">${label}</span>
            </div>
            <span class="font-semibold text-gray-800">${pct}%</span>
        </div>`;
});

// ── Laravel Echo (Real-time) ──
function setupEcho() {
    window.Echo.channel('dashboard-admin')
        .listen('LaporanBaru', (e) => {
            const totalEl = document.getElementById('total-laporan');
            if (totalEl) totalEl.innerText = parseInt(totalEl.innerText) + 1;

            const perluEl = document.getElementById('perlu-verifikasi');
            if (perluEl) perluEl.innerText = parseInt(perluEl.innerText) + 1;

            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById('tbody-laporan');
            if (tbody) {
                const row = `<tr style="animation: fadeIn 0.5s ease;">
                    <td class="text-gray-400 text-xs font-mono">-</td>
                    <td>
                        <div class="font-semibold text-gray-800 text-sm">${e.user_name}</div>
                        <div class="text-xs text-gray-400">Mahasiswa</div>
                    </td>
                    <td>
                        <div class="font-medium text-gray-800">${e.nama_barang}</div>
                    </td>
                    <td class="text-gray-600 text-sm">-</td>
                    <td><span class="badge badge-rendah"><i class="fa-solid fa-circle-info"></i> Rendah</span></td>
                    <td><span class="badge badge-pending">Menunggu</span></td>
                    <td class="text-xs text-gray-500">Baru saja</td>
                    <td>-</td>
                </tr>`;
                tbody.insertAdjacentHTML('afterbegin', row);
            }
        });
}

if (typeof window.Echo !== 'undefined') {
    setupEcho();
} else {
    window.addEventListener('load', function() {
        setTimeout(setupEcho, 500);
    });
}
</script>
@endpush