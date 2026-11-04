@extends('layouts.app')

@section('title', 'Status Laporan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
    --green-dark: #0a4a2e;
    --green-mid: #0f6b3e;
    --green-bright: #16a34a;
    --orange: #f59e0b;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
    --radius: 14px;
    --radius-sm: 8px;
}

body { font-family: 'DM Sans', sans-serif; background: #f0f5f2; }

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlideUp .5s ease both; animation-delay: .05s; }
.anim-2 { animation: fadeSlideUp .5s ease both; animation-delay: .15s; }
.anim-3 { animation: fadeSlideUp .5s ease both; animation-delay: .25s; }
.anim-4 { animation: fadeSlideUp .5s ease both; animation-delay: .35s; }

/* HERO */
.hero-banner {
    background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 60%, #0d7a45 100%);
    padding: 90px 40px 40px;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.hero-banner::before {
    content: ''; position: absolute;
    top: -60px; right: -60px;
    width: 280px; height: 280px;
    background: rgba(255,255,255,0.04); border-radius: 50%;
}
.hero-banner::after {
    content: ''; position: absolute;
    bottom: -50px; left: -50px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,0.03); border-radius: 50%;
}
.breadcrumb-custom {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; margin-bottom: 18px; font-size: 0.8rem;
    color: rgba(255,255,255,0.6); background: none; padding: 0;
}
.breadcrumb-custom a { color: rgba(255,255,255,0.6); text-decoration: none; }
.breadcrumb-custom a:hover { color: rgba(255,255,255,0.9); }
.breadcrumb-custom .sep { color: rgba(255,255,255,0.35); }
.breadcrumb-custom .current { color: rgba(255,255,255,0.9); font-weight: 500; }
.hero-content h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2rem; font-weight: 800; color: white;
    margin-bottom: 10px; line-height: 1.2;
}
.hero-content h1 span { color: var(--orange); }
.hero-content p {
    color: rgba(255,255,255,0.72); font-size: 0.95rem;
    max-width: 520px; margin: 0 auto; line-height: 1.6;
}

/* PAGE BODY */
.page-body { max-width: 860px; margin: 0 auto; padding: 32px 20px 60px; }

/* SEARCH BOX */
.search-box {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}
.search-input {
    flex: 1;
    min-width: 200px;
    padding: 10px 14px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--gray-900);
    background: var(--gray-50);
    outline: none;
    transition: all .2s;
}
.search-input:focus {
    border-color: var(--green-bright);
    background: white;
    box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
}
.search-btn {
    padding: 10px 22px;
    background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
    color: white; font-weight: 700; font-size: 0.875rem;
    border: none; border-radius: var(--radius-sm); cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s;
    box-shadow: 0 4px 12px rgba(15,107,62,0.25);
}
.search-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(15,107,62,0.35); }

/* LAPORAN CARD */
.laporan-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    margin-bottom: 16px;
    overflow: hidden;
    border: 1px solid var(--gray-100);
    transition: all .25s ease;
}
.laporan-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,0.10);
    transform: translateY(-2px);
}

.laporan-card-header {
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    border-bottom: 1px solid var(--gray-100);
}

.tiket-badge {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--green-dark);
    background: #d1fae5;
    padding: 4px 10px;
    border-radius: 20px;
    letter-spacing: 0.04em;
}

.laporan-card-body { padding: 16px 20px; }

.laporan-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.info-item label {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--gray-400);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: block;
    margin-bottom: 3px;
}
.info-item span {
    font-size: 0.875rem;
    color: var(--gray-700);
    font-weight: 500;
}

/* STATUS BADGE */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.status-menunggu { background: #fef3c7; color: #92400e; }
.status-diproses { background: #dbeafe; color: #1e40af; }
.status-selesai  { background: #d1fae5; color: #065f46; }
.status-ditolak  { background: #fee2e2; color: #991b1b; }

/* TINGKAT BADGE */
.tingkat-rendah { background: #f0fdf4; color: #166534; }
.tingkat-sedang { background: #fffbeb; color: #92400e; }
.tingkat-tinggi { background: #fef2f2; color: #991b1b; }

/* PROGRESS TRACKER */
.progress-tracker {
    display: flex;
    align-items: center;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--gray-100);
}
.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}
.progress-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 14px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: var(--gray-200);
    z-index: 0;
}
.progress-step.done:not(:last-child)::after { background: var(--green-bright); }

.step-dot {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 2px solid var(--gray-200);
    background: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700;
    color: var(--gray-400);
    position: relative; z-index: 1;
    transition: all .3s;
}
.progress-step.done .step-dot {
    background: var(--green-bright);
    border-color: var(--green-bright);
    color: white;
}
.progress-step.current .step-dot {
    background: var(--orange);
    border-color: var(--orange);
    color: white;
    box-shadow: 0 0 0 4px rgba(245,158,11,0.2);
}
.step-label-text {
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--gray-400);
    margin-top: 6px;
    text-align: center;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.progress-step.done .step-label-text { color: var(--green-bright); }
.progress-step.current .step-label-text { color: var(--orange); }

/* FOTO GRID KECIL */
.foto-mini-grid {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 12px;
}
.foto-mini {
    width: 52px; height: 52px;
    border-radius: 6px;
    object-fit: cover;
    border: 1.5px solid var(--gray-200);
    cursor: pointer;
    transition: all .2s;
}
.foto-mini:hover { border-color: var(--green-bright); transform: scale(1.05); }

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
}
.empty-state .icon { font-size: 3.5rem; margin-bottom: 16px; }
.empty-state h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--gray-700); margin-bottom: 8px; }
.empty-state p { font-size: 0.875rem; color: var(--gray-400); max-width: 320px; margin: 0 auto 20px; }

/* MODAL FOTO */
.foto-modal {
    display: none;
    position: fixed; inset: 0; z-index: 200;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(4px);
    align-items: center; justify-content: center;
    padding: 20px;
}
.foto-modal.open { display: flex; }
.foto-modal img {
    max-width: 90vw; max-height: 85vh;
    border-radius: 12px; object-fit: contain;
    box-shadow: 0 25px 60px rgba(0,0,0,0.5);
}

@media (max-width: 600px) {
    .hero-banner { padding: 80px 16px 28px; }
    .hero-content h1 { font-size: 1.4rem; }
    .page-body { padding: 20px 12px 50px; }
    .laporan-info-grid { grid-template-columns: 1fr; }
    .search-box { flex-direction: column; }
    .search-input, .search-btn { width: 100%; }
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="hero-banner">
    <div class="breadcrumb-custom anim-1">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="sep">›</span>
        <span class="current">Status Laporan</span>
    </div>
    <div class="hero-content">
        <h1 class="anim-2">Status <span>Laporan</span> Kerusakan</h1>
        <p class="anim-2">Pantau perkembangan laporan kerusakan fasilitas yang telah kamu kirimkan.</p>
    </div>
</div>

{{-- PAGE BODY --}}
<div class="page-body">

    {{-- Search by nomor tiket --}}
    <div class="search-box anim-3">
        <form method="GET" action="{{ route('laporan.status') }}" style="display:flex;gap:12px;flex:1;flex-wrap:wrap;">
            <input type="text" name="tiket" class="search-input"
                   placeholder="Cari berdasarkan nomor tiket (cth: SMF-xxxxx)"
                   value="{{ request('tiket') }}">
            <button type="submit" class="search-btn">🔍 Cari</button>
            @if(request('tiket'))
                <a href="{{ route('laporan.status') }}"
                   style="padding:10px 16px;border:1.5px solid #e5e7eb;border-radius:8px;
                          font-size:0.875rem;color:#6b7280;text-decoration:none;
                          display:flex;align-items:center;background:white;">
                    ✕ Reset
                </a>
            @endif
        </form>
    </div>

    {{-- List Laporan --}}
    <div class="anim-4">
        @forelse($laporans as $laporan)

        @php
            $statusStep = match($laporan->status) {
                'menunggu' => 1,
                'diproses' => 2,
                'selesai'  => 3,
                default    => 1,
            };
        @endphp

        <div class="laporan-card">

            {{-- Header --}}
            <div class="laporan-card-header">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span class="tiket-badge">{{ $laporan->nomor_tiket }}</span>
                    <span style="font-size:0.75rem;color:#9ca3af;">
                        {{ $laporan->created_at->format('d M Y, H:i') }}
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    {{-- Tingkat Kerusakan --}}
                    @if($laporan->tingkat_kerusakan)
                        <span class="status-badge tingkat-{{ $laporan->tingkat_kerusakan }}">
                            @if($laporan->tingkat_kerusakan === 'tinggi') 🔴
                            @elseif($laporan->tingkat_kerusakan === 'sedang') 🟡
                            @else 🟢
                            @endif
                            {{ ucfirst($laporan->tingkat_kerusakan) }}
                        </span>
                    @endif
                    {{-- Status --}}
                    <span class="status-badge status-{{ $laporan->status }}">
                        @if($laporan->status === 'menunggu') ⏳ Menunggu
                        @elseif($laporan->status === 'diproses') 🔄 Diproses
                        @elseif($laporan->status === 'selesai') ✅ Selesai
                        @else ❌ Ditolak
                        @endif
                    </span>
                </div>
            </div>

            {{-- Body --}}
            <div class="laporan-card-body">

                {{-- Info Grid --}}
                <div class="laporan-info-grid">
                    <div class="info-item">
                        <label>Nama Barang</label>
                        <span>{{ $laporan->nama_barang }}</span>
                    </div>
                    <div class="info-item">
                        <label>Jenis Barang</label>
                        <span>{{ ucfirst($laporan->jenis_barang) }}</span>
                    </div>
                    <div class="info-item">
                        <label>Lokasi</label>
                        <span>{{ $laporan->tempat_label ?? $laporan->tempat_utama }}</span>
                    </div>
                    <div class="info-item">
                        <label>Deskripsi</label>
                        <span>{{ Str::limit($laporan->deskripsi, 80) }}</span>
                    </div>
                </div>

                {{-- Catatan Admin --}}
                @if($laporan->catatan_admin)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;
                            padding:10px 14px;margin-bottom:12px;">
                    <p style="font-size:0.75rem;font-weight:700;color:#065f46;
                               margin-bottom:4px;font-family:'Plus Jakarta Sans',sans-serif;">
                        📋 Catatan Admin
                    </p>
                    <p style="font-size:0.82rem;color:#374151;margin:0;">{{ $laporan->catatan_admin }}</p>
                </div>
                @endif

                {{-- Progress Tracker --}}
                <div class="progress-tracker">
                    <div class="progress-step {{ $statusStep >= 1 ? 'done' : '' }} {{ $statusStep === 1 ? 'current' : '' }}">
                        <div class="step-dot">{{ $statusStep > 1 ? '✓' : '1' }}</div>
                        <div class="step-label-text">Menunggu</div>
                    </div>
                    <div class="progress-step {{ $statusStep >= 2 ? 'done' : '' }} {{ $statusStep === 2 ? 'current' : '' }}">
                        <div class="step-dot">{{ $statusStep > 2 ? '✓' : '2' }}</div>
                        <div class="step-label-text">Diproses</div>
                    </div>
                    <div class="progress-step {{ $statusStep >= 3 ? 'done' : '' }} {{ $statusStep === 3 ? 'current' : '' }}">
                        <div class="step-dot">{{ $statusStep >= 3 ? '✓' : '3' }}</div>
                        <div class="step-label-text">Selesai</div>
                    </div>
                </div>

                {{-- Foto Mini --}}
                @php $fotoUrls = $laporan->foto_urls; @endphp
                @if(array_filter($fotoUrls))
                <div class="foto-mini-grid">
                    @foreach($fotoUrls as $sisi => $url)
                        @if($url)
                        <img src="{{ $url }}" alt="Foto {{ $sisi }}"
                             class="foto-mini" title="{{ ucfirst($sisi) }}"
                             onclick="bukaFoto('{{ $url }}')">
                        @endif
                    @endforeach
                </div>
                @endif

            </div>
        </div>

        @empty
        <div class="empty-state">
            <div class="icon">📋</div>
            <h3>Belum Ada Laporan</h3>
            <p>
                @if(request('tiket'))
                    Nomor tiket <strong>{{ request('tiket') }}</strong> tidak ditemukan.
                @else
                    Kamu belum membuat laporan kerusakan apapun.
                @endif
            </p>
            <a href="{{ route('laporan.create') }}"
               style="display:inline-flex;align-items:center;gap:8px;
                      padding:11px 24px;background:linear-gradient(135deg,#0f6b3e,#0a4a2e);
                      color:white;font-weight:700;font-size:0.875rem;border-radius:8px;
                      text-decoration:none;box-shadow:0 4px 14px rgba(15,107,62,0.3);">
                + Buat Laporan
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($laporans->hasPages())
    <div style="margin-top:24px;">
        {{ $laporans->links() }}
    </div>
    @endif

</div>

{{-- Modal Foto --}}
<div class="foto-modal" id="fotoModal" onclick="tutupFoto()">
    <img id="fotoModalImg" src="" alt="Foto">
</div>

@endsection

@push('scripts')
<script>
function bukaFoto(src) {
    document.getElementById('fotoModalImg').src = src;
    document.getElementById('fotoModal').classList.add('open');
}
function tutupFoto() {
    document.getElementById('fotoModal').classList.remove('open');
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupFoto(); });
</script>
@endpush