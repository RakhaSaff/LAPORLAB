{{-- resources/views/admin/import/preview.blade.php --}}
@extends('layouts.admin')

@section('title', 'Preview Import')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Preview Import</h1>
        <p class="page-subtitle">Periksa hasil mapping kolom sebelum data disimpan</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.import.cancel') }}" class="btn btn-outline">
            <i class="ti ti-x"></i> Batal
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
@endif

{{-- ── Summary Bar ─────────────────────────────────────────────────── --}}
<div class="summary-bar">
    <div class="summary-item">
        <span class="summary-label">Total baris</span>
        <span class="summary-value">{{ $total }}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Format file</span>
        <span class="summary-value">{{ strtoupper($extension) }}</span>
    </div>
    <div class="summary-item success">
        <span class="summary-label">Kolom terdeteksi</span>
        <span class="summary-value">{{ count($kolom['terdeteksi']) }}</span>
    </div>
    @if(count($kolom['tidakDikenal']) > 0)
    <div class="summary-item warning">
        <span class="summary-label">Kolom tidak dikenal</span>
        <span class="summary-value">{{ count($kolom['tidakDikenal']) }}</span>
    </div>
    @endif
</div>

<div class="preview-grid">

    {{-- ── Mapping Kolom ───────────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="ti ti-arrows-exchange"></i> Mapping Kolom</h2>
            <p class="card-subtitle">Kolom dari file kamu → Field LAPORLAB</p>
        </div>
        <div class="card-body">
            @if(count($kolom['terdeteksi']) > 0)
                <p class="mapping-section-title success-text"><i class="ti ti-circle-check"></i> Terdeteksi otomatis</p>
                <div class="mapping-list">
                    @foreach($kolom['terdeteksi'] as $asal => $tujuan)
                        @if(!str_starts_with($tujuan, '_'))
                        <div class="mapping-row">
                            <span class="mapping-from">{{ $asal }}</span>
                            <i class="ti ti-arrow-right mapping-arrow"></i>
                            <span class="mapping-to">{{ $tujuan }}</span>
                            <span class="mapping-badge ok"><i class="ti ti-check"></i></span>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif

            @if(count($kolom['tidakDikenal']) > 0)
                <p class="mapping-section-title warning-text" style="margin-top:1rem"><i class="ti ti-alert-triangle"></i> Tidak dikenal (akan diabaikan)</p>
                <div class="mapping-list">
                    @foreach($kolom['tidakDikenal'] as $col)
                        <div class="mapping-row ignored">
                            <span class="mapping-from muted">{{ $col }}</span>
                            <i class="ti ti-arrow-right mapping-arrow muted"></i>
                            <span class="mapping-to muted">diabaikan</span>
                            <span class="mapping-badge skip"><i class="ti ti-minus"></i></span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Preview Data ─────────────────────────────────────────────── --}}
    <div class="card card-preview-data">
        <div class="card-header preview-header-flex">
            <div>
                <h2 class="card-title"><i class="ti ti-table"></i> Preview Data</h2>
                <p class="card-subtitle">
                    Menampilkan {{ $preview->firstItem() ?? 0 }}–{{ $preview->lastItem() ?? 0 }} dari {{ $total }} baris
                </p>
            </div>
            <form method="GET" action="{{ route('admin.import.preview.show') }}" id="perPageForm">
                <label for="per_page" class="per-page-label">Tampilkan</label>
                <select name="per_page" id="per_page" class="per-page-select" onchange="document.getElementById('perPageForm').submit()">
                    @foreach($allowedPerPage as $opt)
                        <option value="{{ $opt }}" {{ $perPage == $opt ? 'selected' : '' }}>{{ $opt }} baris</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrapper">
                <table class="preview-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>No.Inv</th>
                            <th>Jenis</th>
                            <th>Lokasi</th>
                            <th>Deskripsi</th>
                            <th>Tingkat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($preview as $i => $row)
                            <tr>
                                <td class="row-num">{{ $preview->firstItem() + $i }}</td>
                                <td>
                                    @if(!empty($row['nama_barang']))
                                        {{ Str::limit($row['nama_barang'], 30) }}
                                    @else
                                        <span class="empty-cell">—</span>
                                    @endif
                                </td>
                                <td class="font-mono">
                                    {{ !empty($row['kode_barang']) ? $row['kode_barang'] : '-' }}
                                </td>
                                <td>
                                    @if(!empty($row['jenis_barang']))
                                        <span class="badge badge-jenis-{{ $row['jenis_barang'] === 'elektronik' ? 'e' : 'ne' }}">
                                            {{ $row['jenis_barang'] }}
                                        </span>
                                    @else
                                        <span class="empty-cell">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{ !empty($row['tempat_utama'])
                                        ? ucwords(str_replace('_', ' ', $row['tempat_utama']))
                                        : '—' }}
                                    @if(!empty($row['sub_lokasi']))
                                        <br><small class="muted">{{ $row['sub_lokasi'] }}</small>
                                    @endif
                                </td>
                                <td>{{ !empty($row['deskripsi']) ? Str::limit($row['deskripsi'], 50) : '—' }}</td>
                                <td>
                                    @if(!empty($row['tingkat_kerusakan']))
                                        <span class="badge badge-tingkat-{{ $row['tingkat_kerusakan'] }}">
                                            {{ ucfirst($row['tingkat_kerusakan']) }}
                                        </span>
                                    @else
                                        <span class="empty-cell">auto</span>
                                    @endif
                                </td>
                                <td>
                                    @php $st = $row['status'] ?? 'menunggu'; @endphp
                                    <span class="badge badge-status-{{ $st }}">{{ ucfirst($st) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="empty-state">Tidak ada data untuk ditampilkan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── Pagination ────────────────────────────────────────── --}}
            @if($preview->lastPage() > 1)
            <div class="pagination-bar">
                <a href="{{ $preview->previousPageUrl() ?? '#' }}"
                   class="btn btn-outline btn-sm {{ $preview->onFirstPage() ? 'disabled' : '' }}">
                    <i class="ti ti-chevron-left"></i> Sebelumnya
                </a>

                <div class="pagination-pages">
                    @php
                        $current = $preview->currentPage();
                        $last    = $preview->lastPage();
                        $start   = max(1, $current - 2);
                        $end     = min($last, $current + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $preview->url(1) }}" class="page-link">1</a>
                        @if($start > 2)<span class="page-dots">…</span>@endif
                    @endif

                    @for($p = $start; $p <= $end; $p++)
                        <a href="{{ $preview->url($p) }}" class="page-link {{ $p == $current ? 'active' : '' }}">{{ $p }}</a>
                    @endfor

                    @if($end < $last)
                        @if($end < $last - 1)<span class="page-dots">…</span>@endif
                        <a href="{{ $preview->url($last) }}" class="page-link">{{ $last }}</a>
                    @endif
                </div>

                <a href="{{ $preview->nextPageUrl() ?? '#' }}"
                   class="btn btn-outline btn-sm {{ !$preview->hasMorePages() ? 'disabled' : '' }}">
                    Selanjutnya <i class="ti ti-chevron-right"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ── Action Buttons ───────────────────────────────────────────────── --}}
<div class="action-bar">
    <a href="{{ route('admin.import.cancel') }}" class="btn btn-outline">
        <i class="ti ti-arrow-left"></i> Kembali & Upload Ulang
    </a>
    <form action="{{ route('admin.import.store') }}" method="POST" id="importForm">
        @csrf
        <button type="submit" class="btn btn-primary" id="importBtn">
            <i class="ti ti-database-import"></i>
            Import {{ $total }} Laporan ke LAPORLAB
        </button>
    </form>
</div>
@endsection

@push('styles')
<style>
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.25rem; }
.page-title { font-size: 1.5rem; font-weight: 600; color: #1a1a1a; margin: 0; }
.page-subtitle { color: #6b7280; font-size: 0.875rem; margin: 0.25rem 0 0; }

.summary-bar { display: flex; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.summary-item { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 0.75rem 1.25rem; }
.summary-item.success { background: #dcfce7; border-color: #bbf7d0; }
.summary-item.warning { background: #fef9c3; border-color: #fde68a; }
.summary-label { font-size: 0.75rem; color: #6b7280; display: block; }
.summary-value { font-size: 1.25rem; font-weight: 600; color: #1a1a1a; }
.summary-item.success .summary-value { color: #15803d; }
.summary-item.warning .summary-value { color: #92400e; }

.preview-grid { display: grid; grid-template-columns: 320px 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
.card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
.card-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6; }
.card-title { font-size: 0.9375rem; font-weight: 600; color: #1a1a1a; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
.card-subtitle { font-size: 0.8125rem; color: #9ca3af; margin: 0.25rem 0 0; }
.card-body { padding: 1.25rem; }
.card-body.no-pad { padding: 0; }

/* Preview header (title + per-page selector) */
.preview-header-flex { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
.per-page-label { font-size: 0.8125rem; color: #6b7280; margin-right: 0.5rem; }
.per-page-select { padding: 0.375rem 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.8125rem; color: #374151; }

/* Mapping */
.mapping-section-title { font-size: 0.8125rem; font-weight: 600; margin: 0 0 0.625rem; display: flex; align-items: center; gap: 0.375rem; }
.success-text { color: #15803d; }
.warning-text { color: #92400e; }
.mapping-list { display: flex; flex-direction: column; gap: 0.375rem; }
.mapping-row { display: flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0.625rem; background: #f9fafb; border-radius: 6px; font-size: 0.8125rem; }
.mapping-row.ignored { opacity: 0.6; }
.mapping-from { font-family: monospace; color: #374151; flex: 1; }
.mapping-arrow { color: #9ca3af; font-size: 0.75rem; }
.mapping-to { font-family: monospace; color: #15803d; flex: 1; }
.mapping-badge { width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.625rem; }
.mapping-badge.ok { background: #dcfce7; color: #15803d; }
.mapping-badge.skip { background: #f3f4f6; color: #9ca3af; }
.muted { color: #9ca3af !important; }

/* Table */
.table-wrapper { overflow-x: auto; }
.preview-table { width: 100%; border-collapse: collapse; font-size: 0.8125rem; }
.preview-table th { background: #f9fafb; padding: 0.625rem 0.875rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
.preview-table td { padding: 0.625rem 0.875rem; border-bottom: 1px solid #f3f4f6; color: #374151; vertical-align: top; }
.preview-table tr:last-child td { border-bottom: none; }
.row-num { color: #9ca3af; font-weight: 500; }
.empty-cell { color: #d1d5db; font-style: italic; }
.empty-state { text-align: center; color: #9ca3af; padding: 2rem; }

/* Pagination */
.pagination-bar { display: flex; justify-content: space-between; align-items: center; padding: 0.875rem; border-top: 1px solid #f3f4f6; gap: 0.75rem; flex-wrap: wrap; }
.btn-sm { padding: 0.4rem 0.75rem; font-size: 0.8125rem; }
.btn.disabled { opacity: 0.4; pointer-events: none; }
.pagination-pages { display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap; justify-content: center; }
.page-link { padding: 0.375rem 0.625rem; border-radius: 6px; font-size: 0.8125rem; color: #374151; text-decoration: none; min-width: 28px; text-align: center; }
.page-link:hover { background: #f3f4f6; }
.page-link.active { background: #16a34a; color: #fff; font-weight: 600; }
.page-dots { color: #9ca3af; padding: 0 0.25rem; }

/* Badges */
.badge { display: inline-flex; align-items: center; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.6875rem; font-weight: 500; }
.badge-jenis-e { background: #dbeafe; color: #1d4ed8; }
.badge-jenis-ne { background: #f3f4f6; color: #374151; }
.badge-tingkat-rendah { background: #dcfce7; color: #15803d; }
.badge-tingkat-sedang { background: #fef9c3; color: #854d0e; }
.badge-tingkat-tinggi { background: #fee2e2; color: #dc2626; }
.badge-status-menunggu { background: #f3f4f6; color: #374151; }
.badge-status-diverifikasi { background: #dbeafe; color: #1d4ed8; }
.badge-status-diproses { background: #fef9c3; color: #854d0e; }
.badge-status-selesai { background: #dcfce7; color: #15803d; }
.badge-status-ditolak { background: #fee2e2; color: #dc2626; }

/* Action bar */
.action-bar { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; }
.btn { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.625rem 1.25rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: 1px solid transparent; transition: all 0.15s; text-decoration: none; }
.btn-primary { background: #16a34a; color: #fff; border-color: #16a34a; }
.btn-primary:hover { background: #15803d; }
.btn-outline { background: #fff; color: #374151; border-color: #d1d5db; }
.btn-outline:hover { background: #f9fafb; }
.alert { display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; }
.alert-danger { background: #fee2e2; color: #dc2626; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('importForm').addEventListener('submit', function() {
    const btn = document.getElementById('importBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="ti ti-loader-2" style="animation:spin 1s linear infinite"></i> Mengimport...';
});
</script>
<style>@keyframes spin { to { transform: rotate(360deg); } }</style>
@endpush