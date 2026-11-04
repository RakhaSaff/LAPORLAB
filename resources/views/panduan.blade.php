@extends('layouts.app')

@section('title', 'Panduan — LAPORLAB Saintek')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
    --green-dark:   #0a4a2e;
    --green-mid:    #0f6b3e;
    --green-bright: #16a34a;
    --green-light:  #22c55e;
    --orange:       #f59e0b;
    --orange-dark:  #d97706;
    --gray-50:  #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --radius:    14px;
    --radius-sm: 8px;
    --shadow:    0 4px 20px rgba(0,0,0,0.08);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
}

body { font-family: 'DM Sans', sans-serif; background: #f0f5f2; }

/* ── ANIMATIONS ── */
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation: fadeSlideUp .55s ease both; animation-delay: .05s; }
.anim-2 { animation: fadeSlideUp .55s ease both; animation-delay: .15s; }
.anim-3 { animation: fadeSlideUp .55s ease both; animation-delay: .25s; }
.anim-4 { animation: fadeSlideUp .55s ease both; animation-delay: .35s; }
.anim-5 { animation: fadeSlideUp .55s ease both; animation-delay: .45s; }
.anim-6 { animation: fadeSlideUp .55s ease both; animation-delay: .55s; }

/* ── HERO ── */
.hero-panduan {
    background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 60%, #0d7a45 100%);
    padding: 90px 40px 48px;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.hero-panduan::before {
    content: '';
    position: absolute; top: -60px; right: -60px;
    width: 280px; height: 280px;
    background: rgba(255,255,255,0.04); border-radius: 50%;
}
.hero-panduan::after {
    content: '';
    position: absolute; bottom: -50px; left: -50px;
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
.hero-panduan h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.2rem; font-weight: 800; color: white;
    margin-bottom: 14px; line-height: 1.2;
}
.hero-panduan h1 span { color: var(--orange); }
.hero-panduan p {
    color: rgba(255,255,255,0.75); font-size: 0.95rem;
    max-width: 580px; margin: 0 auto; line-height: 1.65;
}

/* ── PAGE BODY ── */
.page-body { max-width: 860px; margin: 0 auto; padding: 40px 20px 72px; }

/* ── INTRO CARD ── */
.intro-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 32px 36px;
    margin-bottom: 32px;
    border-left: 5px solid var(--green-bright);
}
.intro-card p {
    font-size: 0.93rem; line-height: 1.75; color: var(--gray-700); margin: 0;
}

/* ── SECTION TITLE ── */
.section-label {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 14px;
}
.section-label .label-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: grid; place-items: center;
    font-size: 1.1rem; flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.section-label h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.05rem; font-weight: 700; color: var(--green-dark); margin: 0;
}
.section-label p { font-size: 0.8rem; color: var(--gray-500); margin: 2px 0 0; }

/* ── FLOW CARD ── */
.flow-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); overflow: hidden;
    margin-bottom: 20px;
}
.flow-card-header {
    display: flex; align-items: center; gap: 14px;
    padding: 18px 24px;
}
.flow-step-num {
    width: 40px; height: 40px; border-radius: 50%;
    display: grid; place-items: center;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800; font-size: 1rem; color: white;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.flow-card-header .flow-meta h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.97rem; font-weight: 700; color: var(--gray-900); margin: 0;
}
.flow-card-header .flow-meta p {
    font-size: 0.78rem; color: var(--gray-500); margin: 2px 0 0;
}
.flow-card-body {
    padding: 0 24px 20px;
}
.flow-card-body p {
    font-size: 0.88rem; color: var(--gray-700); line-height: 1.7; margin: 0 0 14px;
}

/* ── FLOW ARROW TRAIL ── */
.flow-trail {
    display: flex; flex-wrap: wrap; align-items: center;
    gap: 6px; margin-top: 4px;
}
.trail-node {
    display: inline-flex; align-items: center;
    padding: 6px 13px;
    border-radius: 6px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.75rem; font-weight: 700;
    white-space: nowrap;
}
.trail-arrow {
    font-size: 0.85rem; color: var(--gray-400); flex-shrink: 0;
}

/* ── STEP COLOURS ── */
.step-blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.step-green  { background: linear-gradient(135deg, #16a34a, #0a4a2e); }
.step-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
.step-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.step-teal   { background: linear-gradient(135deg, #0d9488, #0f766e); }

.node-blue   { background: rgba(59,130,246,0.1);  color: #1d4ed8; }
.node-green  { background: rgba(22,163,74,0.1);   color: #0a4a2e; }
.node-orange { background: rgba(245,158,11,0.1);  color: #92400e; }
.node-purple { background: rgba(139,92,246,0.1);  color: #5b21b6; }
.node-teal   { background: rgba(13,148,136,0.1);  color: #0f766e; }

/* ── SUMMARY BOX ── */
.summary-box {
    background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
    border-radius: var(--radius); padding: 28px 32px;
    color: white; margin-top: 32px;
}
.summary-box h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.05rem; font-weight: 700; margin: 0 0 16px;
    display: flex; align-items: center; gap: 8px;
}
.summary-box ul {
    list-style: none; padding: 0; margin: 0;
    display: flex; flex-direction: column; gap: 10px;
}
.summary-box ul li {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: 0.875rem; line-height: 1.6; color: rgba(255,255,255,0.88);
}
.summary-box ul li::before {
    content: '✓';
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(255,255,255,0.18); font-size: 0.7rem;
    font-weight: 700; color: #6ee7b7; flex-shrink: 0; margin-top: 2px;
}

/* ── BACK BTN ── */
.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 11px 22px; text-decoration: none;
    border: 1.5px solid var(--gray-200); background: white;
    color: var(--gray-700); font-size: 0.875rem; font-weight: 600;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s; margin-top: 28px; display: inline-flex;
}
.btn-back:hover { border-color: var(--gray-300); background: var(--gray-50); color: var(--gray-700); }

@media (max-width: 600px) {
    .hero-panduan { padding: 80px 16px 32px; }
    .hero-panduan h1 { font-size: 1.45rem; }
    .page-body { padding: 24px 14px 56px; }
    .intro-card { padding: 20px 18px; }
    .flow-card-body { padding: 0 16px 16px; }
    .flow-card-header { padding: 16px; }
    .summary-box { padding: 20px 18px; }
    .trail-node { font-size: 0.68rem; padding: 5px 10px; }
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="hero-panduan">
    <div class="breadcrumb-custom anim-1">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="sep">›</span>
        <span class="current">Panduan</span>
    </div>
    <h1 class="anim-2">Cara Kerja <span>LAPORLAB</span> Saintek</h1>
    <p class="anim-3">Pahami alur pelaporan kerusakan fasilitas dari awal hingga selesai — cepat, terstruktur, dan terdokumentasi.</p>
</div>

<div class="page-body">

    {{-- INTRO --}}
    <div class="intro-card anim-2">
        <p>
            LAPORLAB Saintek merupakan aplikasi berbasis web yang dirancang untuk membantu proses pelaporan dan penanganan kerusakan fasilitas di lingkungan Fakultas Sains dan Teknologi UIN Sunan Ampel Surabaya secara lebih cepat, terstruktur, dan terdokumentasi dengan baik. Aplikasi ini memungkinkan mahasiswa melaporkan kerusakan fasilitas secara langsung melalui smartphone dengan mengunggah foto, memilih kategori kerusakan, serta menentukan lokasi fasilitas yang mengalami kerusakan.
        </p>
    </div>

    {{-- STEP 1 --}}
    <div class="flow-card anim-3">
        <div class="flow-card-header">
            <div class="flow-step-num step-blue">1</div>
            <div class="flow-meta">
                <h3>Pengguna Mengisi & Mengirim Laporan</h3>
                <p>Mahasiswa</p>
            </div>
        </div>
        <div class="flow-card-body">
            <p>
                Proses kerja aplikasi dimulai ketika pengguna menemukan fasilitas yang rusak, kemudian membuka aplikasi LAPORLAB dan mengisi form laporan yang tersedia. Setelah laporan dikirim, data akan tersimpan secara otomatis ke dalam database sistem.
            </p>
            <div class="flow-trail">
                <span class="trail-node node-blue"> Pengguna (Mahasiswa)</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-blue"> Mengisi Form Laporan</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-blue"> Mengunggah Foto Kerusakan</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-blue"> Data Tersimpan dalam Database</span>
            </div>
        </div>
    </div>

    {{-- STEP 2 --}}
    <div class="flow-card anim-4">
        <div class="flow-card-header">
            <div class="flow-step-num step-purple">2</div>
            <div class="flow-meta">
                <h3>Analisis Prioritas oleh AI</h3>
                <p>Sistem & Kecerdasan Buatan</p>
            </div>
        </div>
        <div class="flow-card-body">
            <p>
                Laporan yang masuk akan dianalisis oleh sistem untuk menentukan tingkat prioritas penanganan dengan bantuan teknologi AI. Hasil analisis tersebut akan membantu stakeholder dalam menentukan laporan mana yang harus segera ditangani.
            </p>
            <div class="flow-trail">
                <span class="trail-node node-purple"> Database Laporan</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-purple"> Analisis Prioritas (AI)</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-purple"> Penentuan Tingkat Prioritas</span>
            </div>
        </div>
    </div>

    {{-- STEP 3 --}}
    <div class="flow-card anim-4">
        <div class="flow-card-header">
            <div class="flow-step-num step-orange">3</div>
            <div class="flow-meta">
                <h3>Monitoring & Penugasan Teknisi</h3>
                <p>Dashboard Stakeholder</p>
            </div>
        </div>
        <div class="flow-card-body">
            <p>
                Stakeholder dapat memantau laporan melalui dashboard monitoring dan menugaskan teknisi untuk melakukan perbaikan fasilitas yang rusak. Selama proses perbaikan berlangsung, teknisi dapat memperbarui status pekerjaan pada sistem sehingga progres perbaikan dapat dipantau secara transparan.
            </p>
            <div class="flow-trail">
                <span class="trail-node node-orange"> Dashboard Stakeholder</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-orange"> Monitoring Laporan</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-orange"> Penugasan Teknisi</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-orange"> Proses Perbaikan</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-orange"> Update Status</span>
            </div>
        </div>
    </div>

    {{-- STEP 4 --}}
    <div class="flow-card anim-5">
        <div class="flow-card-header">
            <div class="flow-step-num step-green">4</div>
            <div class="flow-meta">
                <h3>Arsip & Rekapitulasi Laporan</h3>
                <p>Pimpinan Fakultas</p>
            </div>
        </div>
        <div class="flow-card-body">
            <p>
                Setelah perbaikan selesai dilakukan, seluruh data aktivitas akan tersimpan dalam database sebagai arsip serta bahan laporan bagi pimpinan fakultas untuk melakukan monitoring dan evaluasi pengelolaan fasilitas.
            </p>
            <div class="flow-trail">
                <span class="trail-node node-green"> Perbaikan Selesai</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-green"> Data Tersimpan dalam Database</span>
                <span class="trail-arrow">→</span>
                <span class="trail-node node-green"> Rekapitulasi Laporan Fasilitas</span>
            </div>
        </div>
    </div>

    {{-- SUMMARY BOX --}}
    <div class="summary-box anim-6">
        <h3>📌 Ringkasan Manfaat LAPORLAB Saintek</h3>
        <ul>
            <li>Pelaporan kerusakan fasilitas yang lebih cepat dan mudah melalui smartphone.</li>
            <li>Foto kerusakan dari 6 sisi memastikan data laporan lengkap dan akurat.</li>
            <li>Analisis prioritas berbasis AI membantu penanganan laporan secara efisien.</li>
            <li>Dashboard monitoring real-time untuk stakeholder dan pimpinan fakultas.</li>
            <li>Seluruh data terdokumentasi secara otomatis untuk keperluan evaluasi fasilitas.</li>
        </ul>
    </div>

    <a href="{{ route('home') }}" class="btn-back">← Kembali ke Beranda</a>

</div>

@endsection
