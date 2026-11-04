@extends('layouts.app')

@section('title', 'Laporkan Kerusakan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
    --green-dark: #0a4a2e;
    --green-mid: #0f6b3e;
    --green-bright: #16a34a;
    --green-light: #22c55e;
    --orange: #f59e0b;
    --orange-dark: #d97706;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --shadow-md: 0 4px 16px rgba(0,0,0,0.10);
    --radius: 14px;
    --radius-sm: 8px;
}

body { font-family: 'DM Sans', sans-serif; background: #f0f5f2; }

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(28px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.anim-breadcrumb  { animation: fadeSlideUp .5s ease both; animation-delay: .05s; }
.anim-title       { animation: fadeSlideUp .55s ease both; animation-delay: .18s; }
.anim-subtitle    { animation: fadeSlideUp .55s ease both; animation-delay: .30s; }
.anim-infobox     { animation: fadeSlideUp .5s ease both;  animation-delay: .55s; }
.anim-formcard    { animation: fadeSlideUp .6s ease both;  animation-delay: .65s; }

.hero-banner {
    background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 60%, #0d7a45 100%);
    padding: 90px 40px 40px;
    position: relative; overflow: hidden; text-align: center;
}
.hero-banner::before {
    content: ''; position: absolute; top: -60px; right: -60px;
    width: 280px; height: 280px; background: rgba(255,255,255,0.04); border-radius: 50%;
}
.hero-banner::after {
    content: ''; position: absolute; bottom: -50px; left: -50px;
    width: 220px; height: 220px; background: rgba(255,255,255,0.03); border-radius: 50%;
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
    font-size: 2.1rem; font-weight: 800; color: white;
    margin-bottom: 10px; line-height: 1.2;
}
.hero-content h1 span { color: var(--orange); }
.hero-content p {
    color: rgba(255,255,255,0.72); font-size: 0.95rem;
    max-width: 520px; margin: 0 auto; line-height: 1.6;
}

/* ── Alert Sukses ── */
.alert-success-banner {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border-left: 5px solid #16a34a;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    animation: fadeSlideDown .4s ease both;
}
.alert-success-banner .alert-inner {
    display: flex; align-items: center; gap: 12px;
}
.alert-success-banner .alert-icon { font-size: 1.5rem; flex-shrink: 0; }
.alert-success-banner .alert-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700; color: #14532d; font-size: 0.92rem;
}
.alert-success-banner .alert-msg {
    color: #166534; font-size: 0.8rem; margin-top: 2px;
}
.alert-close-btn {
    background: none; border: none; cursor: pointer;
    color: #16a34a; font-size: 1.2rem; font-weight: 700;
    width: 32px; height: 32px; border-radius: 6px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
}
.alert-close-btn:hover { background: rgba(22,163,74,0.15); }

.page-body { max-width: 900px; margin: 0 auto; padding: 32px 20px 60px; }

.info-box {
    background: linear-gradient(135deg, rgba(22,163,74,0.07) 0%, rgba(15,107,62,0.04) 100%);
    border: 1px solid rgba(22,163,74,0.18);
    border-radius: var(--radius-sm); padding: 12px 16px;
    display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px;
}
.info-box p { font-size: 0.8rem; color: var(--green-dark); line-height: 1.5; margin: 0; }

.form-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow-md); overflow: hidden; }
.form-card-header {
    background: linear-gradient(90deg, var(--green-dark) 0%, var(--green-mid) 100%);
    padding: 20px 28px; display: flex; align-items: center; gap: 14px;
}
.form-card-icon {
    width: 44px; height: 44px; background: rgba(255,255,255,0.15);
    border-radius: 10px; display: grid; place-items: center;
    font-size: 1.25rem; flex-shrink: 0;
}
.form-card-header h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.05rem; font-weight: 700; color: white; margin: 0; }
.form-card-header p { font-size: 0.8rem; color: rgba(255,255,255,0.65); margin: 2px 0 0; }
.form-card-body { padding: 28px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 20px; }
.field:last-child { margin-bottom: 0; }
.field label { font-size: 0.82rem; font-weight: 600; color: var(--gray-700); font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; gap: 6px; }
.field label .req { color: #ef4444; }

.form-control-custom, .form-select-custom {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--gray-200); border-radius: var(--radius-sm);
    font-size: 0.875rem; font-family: 'DM Sans', sans-serif;
    color: var(--gray-900); background: var(--gray-50);
    transition: border-color .2s, box-shadow .2s, background .2s;
    outline: none; appearance: none; -webkit-appearance: none;
}
.form-control-custom:focus, .form-select-custom:focus {
    border-color: var(--green-bright); background: white;
    box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
}
.char-count { text-align: right; font-size: 0.73rem; color: var(--gray-400); margin-top: 3px; font-family: 'Plus Jakarta Sans', sans-serif; }
.char-count.warn { color: var(--orange-dark); }
.char-count.over { color: #ef4444; }

.select-wrap { position: relative; }
.select-wrap::after {
    content: '▾'; position: absolute; right: 13px; top: 50%;
    transform: translateY(-50%); color: var(--gray-400);
    pointer-events: none; font-size: 0.85rem;
}
.form-select-custom { padding-right: 34px; cursor: pointer; }
textarea.form-control-custom { resize: vertical; min-height: 100px; line-height: 1.55; }

.place-selector { display: flex; flex-direction: column; gap: 8px; }

/* ── Kegiatan Akademik Dropdown ── */
#kegiatanAkademikWrap {
    animation: fadeSlideIn 0.3s ease both;
}

.kegiatan-akademik-info {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 10px 14px;
    background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(217,119,6,0.05) 100%);
    border: 1px solid rgba(245,158,11,0.25);
    border-radius: var(--radius-sm);
    font-size: 0.75rem;
    color: #92400e;
    line-height: 1.5;
    margin-top: 4px;
}
.kegiatan-akademik-info span { font-size: 0.9rem; flex-shrink: 0; }

/* ── Radio kegiatan ── */
.radio-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.radio-option {
    flex: 1;
    min-width: 140px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.2s;
    background: var(--gray-50);
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--gray-700);
    font-family: 'Plus Jakarta Sans', sans-serif;
    user-select: none;
}
.radio-option:hover {
    border-color: var(--green-bright);
    background: rgba(22,163,74,0.04);
}
.radio-option input[type="radio"] {
    accent-color: var(--green-bright);
    width: 16px; height: 16px;
    flex-shrink: 0;
}
.radio-option.selected-yes {
    border-color: var(--green-bright);
    background: rgba(22,163,74,0.07);
    color: var(--green-dark);
}
.radio-option.selected-no {
    border-color: #ef4444;
    background: rgba(239,68,68,0.05);
    color: #991b1b;
}

.photo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 4px; }

.photo-slot-wrap { display: flex; flex-direction: column; gap: 6px; }

.photo-slot {
    aspect-ratio: 1;
    border: 2px dashed var(--gray-200);
    border-radius: var(--radius-sm);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    cursor: pointer; transition: all .2s;
    background: var(--gray-50);
    position: relative; overflow: hidden; gap: 6px;
}
.photo-slot:hover { border-color: var(--green-bright); background: rgba(22,163,74,0.04); }
.photo-slot.has-file { border-style: solid; border-color: var(--green-bright); }
.photo-slot input[type="file"] { display: none; }
.photo-slot .slot-icon { font-size: 1.5rem; line-height: 1; pointer-events: none; }
.photo-slot .slot-label { font-size: 0.72rem; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.05em; font-family: 'Plus Jakarta Sans', sans-serif; pointer-events: none; }
.photo-slot .slot-hint { font-size: 0.68rem; color: var(--gray-400); pointer-events: none; }
.photo-slot .preview-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; pointer-events: none; }
.photo-slot .preview-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,0.45);
    display: none; align-items: center; justify-content: center;
    color: white; font-size: 0.72rem; font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif; gap: 4px; pointer-events: none;
}
.photo-slot:hover .preview-overlay { display: flex; }

.check-badge {
    position: absolute; top: 6px; right: 6px;
    width: 20px; height: 20px; background: var(--green-bright); border-radius: 50%;
    display: none; place-items: center; color: white; font-size: 0.65rem; font-weight: 700; z-index: 2; pointer-events: none;
}
.photo-slot.has-file .check-badge { display: grid; }

.delete-btn {
    position: absolute; top: 6px; left: 6px; width: 24px; height: 24px;
    background: #ef4444; border-radius: 50%; display: none; place-items: center;
    color: white; font-size: 0.75rem; font-weight: 700; z-index: 3;
    cursor: pointer; border: none; pointer-events: all;
    box-shadow: 0 2px 6px rgba(239,68,68,0.4); transition: all .2s;
}
.delete-btn:hover { background: #dc2626; transform: scale(1.1); }
.photo-slot.has-file .delete-btn { display: grid; }

.camera-btn {
    width: 100%;
    padding: 6px 0;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: var(--radius-sm);
    color: var(--green-dark);
    font-size: 0.72rem;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all .2s;
}
.camera-btn:hover { background: #dcfce7; border-color: var(--green-bright); }

.form-divider { height: 1px; background: var(--gray-100); margin: 24px 0; }

.form-actions {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 28px; background: var(--gray-50); border-top: 1px solid var(--gray-100);
}
.btn-back-custom {
    display: flex; align-items: center; gap: 7px; padding: 11px 22px;
    border: 1.5px solid var(--gray-200); background: white; color: var(--gray-700);
    font-size: 0.875rem; font-weight: 600; cursor: pointer;
    border-radius: var(--radius-sm); font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s; text-decoration: none;
}
.btn-back-custom:hover { border-color: var(--gray-300); background: var(--gray-50); color: var(--gray-700); }
.btn-submit-custom {
    display: flex; align-items: center; gap: 8px; padding: 12px 30px;
    border: none; background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-dark) 100%);
    color: white; font-size: 0.9rem; font-weight: 700; cursor: pointer;
    border-radius: var(--radius-sm); font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s; box-shadow: 0 4px 14px rgba(15,107,62,0.3);
}
.btn-submit-custom:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(15,107,62,0.4); color: white; }
.btn-submit-custom:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

/* Modal Webcam */
.webcam-modal {
    display: none; position: fixed; inset: 0; z-index: 200;
    background: rgba(0,0,0,0.8); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; padding: 20px;
}
.webcam-modal.open { display: flex; }
.webcam-modal-inner {
    background: white; border-radius: 1.25rem; padding: 1.5rem;
    max-width: 500px; width: 100%; box-shadow: 0 25px 60px rgba(0,0,0,0.3);
}
.webcam-modal-header {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;
}
.webcam-modal-header h3 { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin: 0; }
.webcam-close-btn {
    width: 2rem; height: 2rem; border-radius: 50%; border: none;
    background: var(--gray-100); cursor: pointer; font-size: 1rem;
    display: flex; align-items: center; justify-content: center;
}

@media (max-width: 600px) {
    .hero-banner { padding: 80px 16px 28px; }
    .hero-content h1 { font-size: 1.35rem; }
    .page-body { padding: 20px 12px 50px; }
    .form-card-body { padding: 18px; }
    .form-row { grid-template-columns: 1fr; }
    .photo-grid { grid-template-columns: repeat(2, 1fr); }
    .form-actions { flex-direction: column; gap: 12px; }
    .btn-back-custom, .btn-submit-custom { width: 100%; justify-content: center; }
    .radio-group { flex-direction: column; }
    .radio-option { min-width: unset; }
}
</style>
@endpush

@section('content')

{{-- HERO BANNER --}}
<div class="hero-banner">
    <div class="breadcrumb-custom anim-breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="sep">›</span>
        <span class="current">Laporkan Kerusakan</span>
    </div>
    <div class="hero-content">
        <h1 class="anim-title">Laporkan <span>Kerusakan</span> Fasilitas</h1>
        <p class="anim-subtitle">Isi formulir berikut dengan lengkap dan akurat agar tim kami dapat segera menindaklanjuti perbaikan.</p>
    </div>
</div>

{{-- ALERT BANNER SUKSES --}}
@if(session('success'))
<div class="alert-success-banner" id="successAlert">
    <div class="alert-inner">
        <span class="alert-icon">✅</span>
        <div>
            <div class="alert-title">Laporan Berhasil Dikirim!</div>
            <div class="alert-msg">{{ session('success') }}</div>
        </div>
    </div>
    <button type="button" class="alert-close-btn" onclick="tutupAlert()">✕</button>
</div>
<script>
    window.scrollTo({ top: 0, behavior: 'smooth' });
    setTimeout(() => tutupAlert(), 6000);
    function tutupAlert() {
        const el = document.getElementById('successAlert');
        if (!el) return;
        el.style.transition = 'opacity .4s, transform .4s';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-10px)';
        setTimeout(() => el.remove(), 400);
    }
</script>
@endif

{{-- MAIN CONTENT --}}
<div class="page-body">

    <div class="info-box anim-infobox">
        <span style="font-size:1rem;flex-shrink:0;margin-top:1px;"></span>
        <p>Pastikan foto kerusakan diunggah dengan jelas. Klik kotak untuk unggah file, atau klik tombol untuk ambil foto langsung dari kamera.</p>
    </div>

    <div class="form-card anim-formcard">
        <div class="form-card-header">
            <div class="form-card-icon"></div>
            <div>
                <h2>Formulir Pelaporan Kerusakan Fasilitas</h2>
                <p>Fakultas Sains & Teknologi — UIN Sunan Ampel Surabaya</p>
            </div>
        </div>

        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
            @csrf

            <div class="form-card-body">

                {{-- ROW 1: Kode Barang (kiri) | Nama Barang (kanan) --}}
                <div class="form-row">
                    <div class="field">
                        <label>Kode Barang <span class="req">*</span></label>
                        <div id="kodeBarangWrap">
                            <div style="display:flex; gap:8px;">
                                <input type="text" name="kode_barang" id="kodeBarang"
                                       class="form-control-custom @error('kode_barang') is-invalid @enderror"
                                       style="flex:1;"
                                       placeholder="cth: 3.05.02.04.004"
                                       value="{{ old('kode_barang') }}"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();cariKode();}">
                                <button type="button" id="btnCariKode" onclick="cariKode()"
                                        style="padding:0 20px; border:none; border-radius:var(--radius-sm); cursor:pointer;
                                               background:linear-gradient(135deg,var(--green-mid),var(--green-dark)); color:white;
                                               font-weight:700; font-size:0.85rem; font-family:'Plus Jakarta Sans',sans-serif;">
                                    Cari
                                </button>
                            </div>
                            <div style="font-size:0.72rem; color:var(--gray-400); margin-top:3px;">
                                Ketik kode pada label/stiker barang lalu klik Cari, nama barang akan terisi otomatis.
                            </div>
                            <div id="hasilCariKode" style="font-size:0.78rem; margin-top:6px;"></div>
                            @error('kode_barang')
                                <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label>Nama Barang <span class="req">*</span></label>
                        <div class="select-wrap" id="namaBarangSelectWrap">
                            <select name="nama_barang" id="namaBarang"
                                    class="form-select-custom @error('nama_barang') is-invalid @enderror"
                                    onchange="handleNamaBarang()">
                                <option value="" disabled {{ old('nama_barang') ? '' : 'selected' }}>— Pilih nama barang —</option>
                                <option value="AC"                    {{ old('nama_barang') == 'AC'                    ? 'selected' : '' }}>AC</option>
                                <option value="PC"                    {{ old('nama_barang') == 'PC'                    ? 'selected' : '' }}>PC</option>
                                <option value="Meja Laboratorium"     {{ old('nama_barang') == 'Meja Laboratorium'     ? 'selected' : '' }}>Meja Laboratorium</option>
                                <option value="UPS"                   {{ old('nama_barang') == 'UPS'                   ? 'selected' : '' }}>UPS</option>
                                <option value="Loker"                 {{ old('nama_barang') == 'Loker'                 ? 'selected' : '' }}>Loker</option>
                                <option value="CPU"                   {{ old('nama_barang') == 'CPU'                   ? 'selected' : '' }}>CPU</option>
                                <option value="Mouse"                 {{ old('nama_barang') == 'Mouse'                 ? 'selected' : '' }}>Mouse</option>
                                <option value="Keyboard"              {{ old('nama_barang') == 'Keyboard'              ? 'selected' : '' }}>Keyboard</option>
                                <option value="Proyektor"             {{ old('nama_barang') == 'Proyektor'             ? 'selected' : '' }}>Proyektor</option>
                                <option value="Kursi Ruang Kelas"     {{ old('nama_barang') == 'Kursi Ruang Kelas'     ? 'selected' : '' }}>Kursi Ruang Kelas</option>
                                <option value="Kursi Laboratorium"    {{ old('nama_barang') == 'Kursi Laboratorium'    ? 'selected' : '' }}>Kursi Laboratorium</option>
                                <option value="Kabel HDMI"            {{ old('nama_barang') == 'Kabel HDMI'            ? 'selected' : '' }}>Kabel HDMI</option>
                                <option value="ATK"                   {{ old('nama_barang') == 'ATK'                   ? 'selected' : '' }}>ATK</option>
                                <option value="Smart Board"           {{ old('nama_barang') == 'Smart Board'           ? 'selected' : '' }}>Smart Board</option>
                                <option value="Papan Tulis Standing"  {{ old('nama_barang') == 'Papan Tulis Standing'  ? 'selected' : '' }}>Papan Tulis Standing</option>
                                <option value="Meja Kelas"            {{ old('nama_barang') == 'Meja Kelas'            ? 'selected' : '' }}>Meja Kelas</option>
                                <option value="Vas Bunga"             {{ old('nama_barang') == 'Vas Bunga'             ? 'selected' : '' }}>Vas Bunga</option>
                                <option value="Keramik"               {{ old('nama_barang') == 'Keramik'               ? 'selected' : '' }}>Keramik</option>
                                <option value="Kabel Olor"            {{ old('nama_barang') == 'Kabel Olor'            ? 'selected' : '' }}>Kabel Olor</option>
                                <option value="Remote AC"             {{ old('nama_barang') == 'Remote AC'             ? 'selected' : '' }}>Remote AC</option>
                            </select>
                        </div>
                        {{-- Input manual: aktif hanya kalau "isi manual" dicentang (menggantikan dropdown) --}}
                        <input type="text" id="namaBarangManual" name="nama_barang" maxlength="150"
                               class="form-control-custom @error('nama_barang') is-invalid @enderror"
                               placeholder="cth: Proyektor Epson EB-X41"
                               value="{{ old('input_manual') ? old('nama_barang') : '' }}"
                               style="display:none;" disabled>
                        @error('nama_barang')
                            <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Barang yang belum ada di Master Data / belum punya kode --}}
                <div style="margin: -6px 0 20px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.82rem; font-weight:600; color:var(--gray-700); font-family:'Plus Jakarta Sans',sans-serif;">
                        <input type="checkbox" id="inputManual" name="input_manual" value="1"
                               onchange="aktifkanManual(this.checked)"
                               {{ old('input_manual') ? 'checked' : '' }}
                               style="accent-color:var(--green-bright); width:16px; height:16px;">
                        Barang belum punya kode / tidak ada di Master Data (isi manual)
                    </label>

                    <div id="manualWrap" style="display:none; margin-top:12px; animation: fadeSlideIn 0.3s ease both;">
                        <div class="kegiatan-akademik-info" style="margin-top:0; margin-bottom:14px;">
                            <span></span>
                            <span>Tulis nama barang selengkap mungkin, termasuk merk/tipenya, supaya admin mudah mengenalinya. Kode barang boleh dikosongkan kalau memang belum ada.</span>
                        </div>
                        <div class="field" style="margin-bottom:0;">
                            <label>Jenis Barang <span class="req">*</span></label>
                            <div class="select-wrap">
                                <select id="jenisManual" class="form-select-custom"
                                        onchange="document.getElementById('jenisBarangHidden').value = this.value">
                                    <option value="" disabled selected>— Pilih jenis barang —</option>
                                    <option value="elektronik">Elektronik (AC, proyektor, komputer, dll.)</option>
                                    <option value="non-elektronik">Non-elektronik (meja, kursi, lemari, dll.)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden jenis barang, terisi otomatis via JS --}}
                <input type="hidden" name="jenis_barang" id="jenisBarangHidden" value="{{ old('jenis_barang') }}">

                {{-- Pilihan Tempat --}}
                <div class="field">
                    <label>Pilihan Tempat <span class="req">*</span></label>
                    <div class="place-selector">
                        <div class="select-wrap">
                            <select name="tempat_utama" id="tempatUtama"
                                    class="form-select-custom @error('tempat_utama') is-invalid @enderror"
                                    onchange="handleTempatChange()">
                                <option value="" disabled {{ old('tempat_utama') ? '' : 'selected' }}>— Pilih laboratorium —</option>
                                <optgroup label="Fakultas FST">
                                    <option value="lab_fakultas_fst_basic_biology_laboratory" {{ old('tempat_utama') == 'lab_fakultas_fst_basic_biology_laboratory' ? 'selected' : '' }}>Basic Biology Laboratory — Gedung Terpadu FST Lantai 5</option>
                                    <option value="lab_fakultas_fst_basic_chemistry_laboratory" {{ old('tempat_utama') == 'lab_fakultas_fst_basic_chemistry_laboratory' ? 'selected' : '' }}>Basic Chemistry Laboratory — Gedung Terpadu FST Lantai 5</option>
                                    <option value="lab_fakultas_fst_instrumentation_laboratory" {{ old('tempat_utama') == 'lab_fakultas_fst_instrumentation_laboratory' ? 'selected' : '' }}>Instrumentation Laboratory — Gedung Terpadu FST Lantai 4</option>
                                    <option value="lab_fakultas_fst_laboratorium_green_house_i" {{ old('tempat_utama') == 'lab_fakultas_fst_laboratorium_green_house_i' ? 'selected' : '' }}>Laboratorium Green House I — Gedung Laboratorium Saintek Rooftop</option>
                                    <option value="lab_fakultas_fst_laboratorium_green_house_ii" {{ old('tempat_utama') == 'lab_fakultas_fst_laboratorium_green_house_ii' ? 'selected' : '' }}>Laboratorium Green House II — Gedung Laboratorium Saintek Rooftop</option>
                                </optgroup>
                                <optgroup label="Biologi">
                                    <option value="lab_biologi_animal_cell_and_tissue_culture_laboratory" {{ old('tempat_utama') == 'lab_biologi_animal_cell_and_tissue_culture_laboratory' ? 'selected' : '' }}>Animal Cell and Tissue Culture Laboratory — Gedung Terpadu FST Lantai 4</option>
                                    <option value="lab_biologi_microbiology_laboratory" {{ old('tempat_utama') == 'lab_biologi_microbiology_laboratory' ? 'selected' : '' }}>Microbiology Laboratory — Gedung Terpadu FST Lantai 4</option>
                                    <option value="lab_biologi_plant_physiology_laboratory" {{ old('tempat_utama') == 'lab_biologi_plant_physiology_laboratory' ? 'selected' : '' }}>Plant Physiology Laboratory — Gedung Terpadu FST Lantai 4</option>
                                    <option value="lab_biologi_animal_physiology_laboratory" {{ old('tempat_utama') == 'lab_biologi_animal_physiology_laboratory' ? 'selected' : '' }}>Animal Physiology Laboratory — Gedung Terpadu FST Lantai 4</option>
                                    <option value="lab_biologi_plant_cell_and_tissue_culture_laboratory" {{ old('tempat_utama') == 'lab_biologi_plant_cell_and_tissue_culture_laboratory' ? 'selected' : '' }}>Plant Cell and Tissue Culture Laboratory — Gedung Terpadu FST Lantai 4</option>
                                    <option value="lab_biologi_biosystematics_laboratory" {{ old('tempat_utama') == 'lab_biologi_biosystematics_laboratory' ? 'selected' : '' }}>Biosystematics Laboratory — Gedung Terpadu FST Lantai 5</option>
                                    <option value="lab_biologi_ecology_laboratory" {{ old('tempat_utama') == 'lab_biologi_ecology_laboratory' ? 'selected' : '' }}>Ecology Laboratory — Gedung Terpadu FST Lantai 5</option>
                                </optgroup>
                                <optgroup label="Matematika">
                                    <option value="lab_matematika_intelligent_computation_and_vision_laboratory" {{ old('tempat_utama') == 'lab_matematika_intelligent_computation_and_vision_laboratory' ? 'selected' : '' }}>Intelligent Computation and Vision Laboratory — Gedung Terpadu FST Lantai 3</option>
                                    <option value="lab_matematika_falaq_science_laboratory" {{ old('tempat_utama') == 'lab_matematika_falaq_science_laboratory' ? 'selected' : '' }}>Falaq Science Laboratory — Gedung Terpadu FST Lantai 3</option>
                                    <option value="lab_matematika_modelling_and_simulation_laboratory" {{ old('tempat_utama') == 'lab_matematika_modelling_and_simulation_laboratory' ? 'selected' : '' }}>Modelling and Simulation Laboratory — Gedung Terpadu FST Lantai 3</option>
                                </optgroup>
                                <optgroup label="Ilmu Kelautan">
                                    <option value="lab_ilmu_kelautan_marine_natural_product" {{ old('tempat_utama') == 'lab_ilmu_kelautan_marine_natural_product' ? 'selected' : '' }}>Marine Natural Product — Gedung Terpadu FST Lantai 5</option>
                                    <option value="lab_ilmu_kelautan_marine_pollution_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_marine_pollution_laboratory' ? 'selected' : '' }}>Marine Pollution Laboratory — Gedung Terpadu FST Lantai 6</option>
                                    <option value="lab_ilmu_kelautan_microbiology_and_marine_biotechnology_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_microbiology_and_marine_biotechnology_laboratory' ? 'selected' : '' }}>Microbiology and Marine Biotechnology Laboratory — Gedung Terpadu FST Lantai 6</option>
                                    <option value="lab_ilmu_kelautan_coastal_and_marine_spatial_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_coastal_and_marine_spatial_laboratory' ? 'selected' : '' }}>Coastal and Marine Spatial Laboratory — Gedung Terpadu FST Lantai 6</option>
                                    <option value="lab_ilmu_kelautan_ocean_modelling_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_ocean_modelling_laboratory' ? 'selected' : '' }}>Ocean Modelling Laboratory — Gedung Terpadu FST Lantai 6</option>
                                    <option value="lab_ilmu_kelautan_oceanographic_and_meteorological_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_oceanographic_and_meteorological_laboratory' ? 'selected' : '' }}>Oceanographic and Meteorological Laboratory — Gedung Terpadu FST Lantai 6</option>
                                    <option value="lab_ilmu_kelautan_coastal_community_development_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_coastal_community_development_laboratory' ? 'selected' : '' }}>Coastal Community Development Laboratory — Gedung Terpadu FST Lantai 6</option>
                                    <option value="lab_ilmu_kelautan_marine_environmental_laboratory" {{ old('tempat_utama') == 'lab_ilmu_kelautan_marine_environmental_laboratory' ? 'selected' : '' }}>Marine Environmental Laboratory — Gedung Laboratorium Saintek Lantai 1</option>
                                </optgroup>
                                <optgroup label="Teknik Sipil">
                                    <option value="lab_teknik_sipil_hydrology_hydraulics_laboratory" {{ old('tempat_utama') == 'lab_teknik_sipil_hydrology_hydraulics_laboratory' ? 'selected' : '' }}>Hydrology & Hydraulics Laboratory — Gedung Laboratorium Saintek Lantai 1</option>
                                    <option value="lab_teknik_sipil_laboratorium_concrete_dan_material" {{ old('tempat_utama') == 'lab_teknik_sipil_laboratorium_concrete_dan_material' ? 'selected' : '' }}>Laboratorium Concrete dan Material — Gedung Laboratorium Saintek Lantai 1</option>
                                    <option value="lab_teknik_sipil_structure_laboratory" {{ old('tempat_utama') == 'lab_teknik_sipil_structure_laboratory' ? 'selected' : '' }}>Structure Laboratory — Gedung Laboratorium Saintek Lantai 1</option>
                                    <option value="lab_teknik_sipil_transportation_laboratory" {{ old('tempat_utama') == 'lab_teknik_sipil_transportation_laboratory' ? 'selected' : '' }}>Transportation Laboratory — Gedung Laboratorium Saintek Lantai 1</option>
                                    <option value="lab_teknik_sipil_civil_engineering_computer_laboratory" {{ old('tempat_utama') == 'lab_teknik_sipil_civil_engineering_computer_laboratory' ? 'selected' : '' }}>Civil Engineering Computer Laboratory — Gedung Laboratorium Saintek Lantai 3</option>
                                </optgroup>
                                <optgroup label="Teknik Lingkungan">
                                    <option value="lab_teknik_lingkungan_air_quality_and_noise_laboratory" {{ old('tempat_utama') == 'lab_teknik_lingkungan_air_quality_and_noise_laboratory' ? 'selected' : '' }}>Air Quality and Noise Laboratory — Gedung Laboratorium Saintek Lantai 4</option>
                                    <option value="lab_teknik_lingkungan_environmental_chemistry_laboratory" {{ old('tempat_utama') == 'lab_teknik_lingkungan_environmental_chemistry_laboratory' ? 'selected' : '' }}>Environmental Chemistry Laboratory — Gedung Laboratorium Saintek Lantai 4</option>
                                    <option value="lab_teknik_lingkungan_environmental_microbiology_and_biotechnology" {{ old('tempat_utama') == 'lab_teknik_lingkungan_environmental_microbiology_and_biotechnology' ? 'selected' : '' }}>Environmental Microbiology and Biotechnology — Gedung Laboratorium Saintek Lantai 2</option>
                                    <option value="lab_teknik_lingkungan_mapping_and_modelling_laboratory" {{ old('tempat_utama') == 'lab_teknik_lingkungan_mapping_and_modelling_laboratory' ? 'selected' : '' }}>Mapping and Modelling Laboratory — Gedung Laboratorium Saintek Lantai 3</option>
                                    <option value="lab_teknik_lingkungan_sanitation_and_remediation_laboratory" {{ old('tempat_utama') == 'lab_teknik_lingkungan_sanitation_and_remediation_laboratory' ? 'selected' : '' }}>Sanitation and Remediation Laboratory — Gedung Laboratorium Saintek Lantai 2</option>
                                    <option value="lab_teknik_lingkungan_water_quality_laboratory" {{ old('tempat_utama') == 'lab_teknik_lingkungan_water_quality_laboratory' ? 'selected' : '' }}>Water Quality Laboratory — Gedung Laboratorium Saintek Lantai 3</option>
                                </optgroup>
                                <optgroup label="Arsitektur">
                                    <option value="lab_arsitektur_teknik_komputer_arsitektur" {{ old('tempat_utama') == 'lab_arsitektur_teknik_komputer_arsitektur' ? 'selected' : '' }}>Teknik Komputer Arsitektur — Gedung Terpadu FST Lantai 8</option>
                                    <option value="lab_arsitektur_laboratorium_ilmu_bangunan_dan_teknologi_arsitektur" {{ old('tempat_utama') == 'lab_arsitektur_laboratorium_ilmu_bangunan_dan_teknologi_arsitektur' ? 'selected' : '' }}>Laboratorium Ilmu Bangunan dan Teknologi Arsitektur — Gedung Terpadu FST Lantai 8</option>
                                    <option value="lab_arsitektur_laboratorium_studio_desain" {{ old('tempat_utama') == 'lab_arsitektur_laboratorium_studio_desain' ? 'selected' : '' }}>Laboratorium Studio Desain — Gedung Terpadu FST Lantai 8</option>
                                    <option value="lab_arsitektur_laboratorium_desain_dan_bahan_bangunan" {{ old('tempat_utama') == 'lab_arsitektur_laboratorium_desain_dan_bahan_bangunan' ? 'selected' : '' }}>Laboratorium Desain dan Bahan Bangunan — Gedung Terpadu FST Lantai 9</option>
                                    <option value="lab_arsitektur_laboratorium_fotografi_arsitektur" {{ old('tempat_utama') == 'lab_arsitektur_laboratorium_fotografi_arsitektur' ? 'selected' : '' }}>Laboratorium Fotografi Arsitektur — Gedung Terpadu FST Lantai 9</option>
                                </optgroup>
                                <optgroup label="Sistem Informasi">
                                    <option value="lab_sistem_informasi_database_laboratory" {{ old('tempat_utama') == 'lab_sistem_informasi_database_laboratory' ? 'selected' : '' }}>Database Laboratory — Gedung Terpadu FST Lantai 7</option>
                                    <option value="lab_sistem_informasi_it_governance_laboratory" {{ old('tempat_utama') == 'lab_sistem_informasi_it_governance_laboratory' ? 'selected' : '' }}>IT Governance Laboratory — Gedung Terpadu FST Lantai 7</option>
                                    <option value="lab_sistem_informasi_intelligent_system_laboratory" {{ old('tempat_utama') == 'lab_sistem_informasi_intelligent_system_laboratory' ? 'selected' : '' }}>Intelligent System Laboratory — Gedung Terpadu FST Lantai 7</option>
                                    <option value="lab_sistem_informasi_os_networking_laboratory" {{ old('tempat_utama') == 'lab_sistem_informasi_os_networking_laboratory' ? 'selected' : '' }}>OS & Networking Laboratory — Gedung Terpadu FST Lantai 7</option>
                                    <option value="lab_sistem_informasi_programming_laboratory" {{ old('tempat_utama') == 'lab_sistem_informasi_programming_laboratory' ? 'selected' : '' }}>Programming Laboratory — Gedung Terpadu FST Lantai 7</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    @error('tempat_utama')
                        <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Foto Kode Barang — hanya muncul untuk barang tetap --}}
                <div id="fotoKodeWrap" style="display:none; animation: fadeSlideIn 0.3s ease both;">
                    <div class="form-row">
                        <div class="field">
                            <label>Foto Kode Barang <span class="req">*</span></label>
                            <div class="photo-slot-wrap">
                                <label for="foto_kode" class="photo-slot" id="slot-kode" style="aspect-ratio: unset; min-height: 80px; flex-direction: row; gap: 12px; padding: 14px;">
                                    <input type="file" name="foto_kode" id="foto_kode"
                                           accept="image/*" onchange="handlePhoto(this,'slot-kode')">
                                    <span class="slot-icon" style="font-size:1.8rem;"></span>
                                    <div>
                                        <div class="slot-label" style="text-transform:none; letter-spacing:0; font-size:0.8rem;">Foto label/stiker kode barang</div>
                                        <div class="slot-hint" style="font-size:0.72rem;">Untuk validasi admin</div>
                                    </div>
                                    <div class="check-badge">✓</div>
                                    <button type="button" class="delete-btn"
                                            onclick="hapusFoto(event,'slot-kode','foto_kode')">✕</button>
                                    <div class="preview-overlay"> Ganti</div>
                                </label>
                                <button type="button" class="camera-btn"
                                        onclick="bukaKameraSlot('slot-kode','foto_kode')">
                                     Ambil Foto
                                </button>
                            </div>
                            @error('foto_kode')
                                <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- DROPDOWN KEGIATAN AKADEMIK --}}
                <div class="field" id="kegiatanAkademikWrap" style="display:none;">
                    <label>
                         Apakah sedang berlangsung kegiatan akademik? <span class="req">*</span>
                    </label>
                    <div class="radio-group" id="kegiatanRadioGroup">
                        <label class="radio-option" id="opt-ya" onclick="pilihKegiatan('ya')">
                            <input type="radio" name="kegiatan_akademik" value="ya"
                                   {{ old('kegiatan_akademik') == 'ya' ? 'checked' : '' }}>
                             Ya, sedang berlangsung
                        </label>
                        <label class="radio-option" id="opt-tidak" onclick="pilihKegiatan('tidak')">
                            <input type="radio" name="kegiatan_akademik" value="tidak"
                                   {{ old('kegiatan_akademik') == 'tidak' ? 'checked' : '' }}>
                             Tidak ada kegiatan
                        </label>
                    </div>
                    <div class="kegiatan-akademik-info">
                        <span></span>
                        <span>Informasi ini digunakan untuk menentukan tingkat urgensi laporan. Lokasi yang aktif digunakan saat jam kuliah atau pekan ujian akan mendapat prioritas lebih tinggi.</span>
                    </div>
                    @error('kegiatan_akademik')
                        <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-divider"></div>

                {{-- Upload Foto Kerusakan (1 foto) --}}
                <div class="field">
                    <label style="margin-bottom:4px;">Foto Kerusakan <span class="req">*</span></label>
                    <div class="photo-grid" style="grid-template-columns: 1fr; max-width: 360px;">
                        <div class="photo-slot-wrap">
                            <label for="foto_depan" class="photo-slot" id="slot-depan" style="aspect-ratio: 4 / 3;">
                                <input type="file" name="foto_depan" id="foto_depan"
                                       accept="image/*" onchange="handlePhoto(this,'slot-depan')">
                                <span class="slot-icon"></span>
                                <span class="slot-label">Foto Kerusakan</span>
                                <span class="slot-hint">Klik untuk unggah</span>
                                <div class="check-badge">✓</div>
                                <button type="button" class="delete-btn"
                                        onclick="hapusFoto(event,'slot-depan','foto_depan')">✕</button>
                                <div class="preview-overlay"> Ganti</div>
                            </label>
                            <button type="button" class="camera-btn"
                                    onclick="bukaKameraSlot('slot-depan','foto_depan')">
                                 Ambil Foto
                            </button>
                        </div>
                    </div>
                    @error('foto_depan')
                        <div class="text-danger" style="font-size:0.78rem;margin-top:6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-divider"></div>

                {{-- Tingkat Kerusakan --}}
                <div class="field">
                    <label>Tingkat Kerusakan <span class="req">*</span></label>
                    <div class="select-wrap">
                        <select name="tingkat_kerusakan" id="tingkatKerusakan"
                                class="form-select-custom @error('tingkat_kerusakan') is-invalid @enderror">
                            <option value="" disabled {{ old('tingkat_kerusakan') ? '' : 'selected' }}>— Pilih tingkat kerusakan —</option>
                            <option value="perlu_pemeliharaan"  {{ old('tingkat_kerusakan') == 'perlu_pemeliharaan'  ? 'selected' : '' }}>Perlu Pemeliharaan</option>
                            <option value="rusak_ringan"        {{ old('tingkat_kerusakan') == 'rusak_ringan'        ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_sedang"        {{ old('tingkat_kerusakan') == 'rusak_sedang'        ? 'selected' : '' }}>Rusak Sedang</option>
                            <option value="rusak_berat"         {{ old('tingkat_kerusakan') == 'rusak_berat'         ? 'selected' : '' }}>Rusak Berat</option>
                            <option value="rusak_total"         {{ old('tingkat_kerusakan') == 'rusak_total'         ? 'selected' : '' }}>Rusak Total</option>
                            <option value="tidak_layak_pakai"   {{ old('tingkat_kerusakan') == 'tidak_layak_pakai'   ? 'selected' : '' }}>Tidak Layak Pakai</option>
                            <option value="hilang"              {{ old('tingkat_kerusakan') == 'hilang'              ? 'selected' : '' }}>Hilang</option>
                        </select>
                    </div>
                    <div style="font-size:0.72rem; color:var(--gray-400); margin-top:3px;">
                        Pilih kondisi yang paling sesuai berdasarkan foto yang sudah kamu unggah di atas.
                    </div>
                    @error('tingkat_kerusakan')
                        <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-divider"></div>

                {{-- Deskripsi Kerusakan --}}
                <div class="field">
                    <label>Deskripsi Kerusakan <span class="req">*</span></label>
                    <textarea name="deskripsi" id="deskripsi"
                              class="form-control-custom @error('deskripsi') is-invalid @enderror"
                              maxlength="250" rows="4"
                              placeholder="Jelaskan kerusakan secara detail: kondisi barang, bagian yang rusak, kapan pertama kali terlihat, dll."
                              oninput="updateChar('deskripsi','deskCount',250)">{{ old('deskripsi') }}</textarea>
                    <div class="char-count" id="deskCount">{{ strlen(old('deskripsi','')) }} / 250</div>
                    @error('deskripsi')
                        <div class="text-danger" style="font-size:0.78rem;">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('home') }}" class="btn-back-custom">← Kembali</a>
                <button type="submit" class="btn-submit-custom" id="submitBtn">Kirim Laporan →</button>
            </div>

        </form>
    </div>

</div>

{{-- Modal Webcam --}}
<div class="webcam-modal" id="webcamModal">
    <div class="webcam-modal-inner">
        <div class="webcam-modal-header">
            <h3>📷 Ambil Foto</h3>
            <button type="button" class="webcam-close-btn" onclick="tutupKameraSlot()">✕</button>
        </div>
        <video id="webcamVideo" autoplay playsinline
               style="width:100%;border-radius:0.75rem;background:#000;max-height:300px;object-fit:cover;"></video>
        <canvas id="webcamCanvas" style="display:none;"></canvas>
        <div style="display:flex;gap:0.75rem;margin-top:1rem;">
            <button type="button" onclick="tutupKameraSlot()"
                    style="flex:1;padding:0.75rem;border:1.5px solid #e2e8f0;border-radius:0.75rem;
                           background:white;color:#6b7280;font-weight:600;cursor:pointer;
                           font-size:0.875rem;font-family:'Plus Jakarta Sans',sans-serif;">
                Batal
            </button>
            <button type="button" onclick="ambilFotoSlot()"
                    style="flex:2;padding:0.75rem;border-radius:0.75rem;
                           background:linear-gradient(135deg,#0f6b3e,#0a4a2e);
                           color:white;font-weight:700;cursor:pointer;
                           font-size:0.875rem;font-family:'Plus Jakarta Sans',sans-serif;
                           border:none;box-shadow:0 4px 12px rgba(15,107,62,0.3);">
                📸 Ambil Foto
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>

// ── Mapping barang ke jenis ──
const barangTetap = ['AC','PC','Meja Laboratorium','UPS','Loker','CPU','Mouse','Keyboard','Proyektor'];

function handleNamaBarang() {
    const val          = document.getElementById('namaBarang').value;
    const hidden       = document.getElementById('jenisBarangHidden');
    const fotoKodeWrap = document.getElementById('fotoKodeWrap');
    const kodeInput    = document.getElementById('kodeBarang');

    if (!val) {
        hidden.value               = '';
        fotoKodeWrap.style.display = 'none';
        kodeInput.required         = false;
        kodeInput.value            = '';
        return;
    }

    if (barangTetap.includes(val)) {
        hidden.value               = 'elektronik';
        fotoKodeWrap.style.display = 'block';
        kodeInput.required         = true;
    } else {
        hidden.value               = 'non-elektronik';
        fotoKodeWrap.style.display = 'none';
        kodeInput.required         = false;
        kodeInput.value            = '';
    }
}

function handleJenisBarang() {}

// Karena satu-satunya pilihan tempat sekarang adalah laboratorium,
// dropdown "kegiatan akademik" selalu ditampilkan begitu sebuah lab dipilih.
function handleTempatChange() {
    const val    = document.getElementById('tempatUtama').value;
    const kgWrap = document.getElementById('kegiatanAkademikWrap');

    if (val) {
        kgWrap.style.display = 'flex';
    } else {
        kgWrap.style.display = 'none';
        document.querySelectorAll('input[name="kegiatan_akademik"]').forEach(r => r.checked = false);
        document.getElementById('opt-ya').classList.remove('selected-yes');
        document.getElementById('opt-tidak').classList.remove('selected-no');
    }
}

function pilihKegiatan(val) {
    const optYa    = document.getElementById('opt-ya');
    const optTidak = document.getElementById('opt-tidak');
    optYa.classList.remove('selected-yes');
    optTidak.classList.remove('selected-no');
    if (val === 'ya') {
        optYa.classList.add('selected-yes');
        optYa.querySelector('input').checked = true;
    } else {
        optTidak.classList.add('selected-no');
        optTidak.querySelector('input').checked = true;
    }
}

function updateChar(inputId, countId, max) {
    const el      = document.getElementById(inputId);
    const counter = document.getElementById(countId);
    const len     = el.value.length;
    counter.textContent = len + ' / ' + max;
    counter.className   = 'char-count';
    if (len > max * 0.85) counter.classList.add('warn');
    if (len >= max)        counter.classList.add('over');
}

function handlePhoto(input, slotId) {
    if (!input.files || !input.files[0]) return;
    const slot   = document.getElementById(slotId);
    const reader = new FileReader();
    reader.onload = (e) => setSlotPreview(slot, e.target.result);
    reader.readAsDataURL(input.files[0]);
}

function setSlotPreview(slot, src) {
    const oldImg = slot.querySelector('.preview-img');
    if (oldImg) oldImg.remove();
    const img = document.createElement('img');
    img.src = src;
    img.className = 'preview-img';
    slot.insertBefore(img, slot.firstChild);
    slot.classList.add('has-file');
    slot.querySelectorAll('.slot-icon, .slot-label, .slot-hint').forEach(el => el.style.opacity = '0');
}

function hapusFoto(event, slotId, inputId) {
    event.preventDefault();
    event.stopPropagation();
    const slot  = document.getElementById(slotId);
    const input = document.getElementById(inputId);
    const oldImg = slot.querySelector('.preview-img');
    if (oldImg) oldImg.remove();
    input.value = '';
    slot.classList.remove('has-file');
    slot.querySelectorAll('.slot-icon, .slot-label, .slot-hint').forEach(el => el.style.opacity = '1');
    const hiddenInput = document.getElementById('base64_' + inputId);
    if (hiddenInput) hiddenInput.value = '';
}

// ── Validasi submit ──
document.getElementById('reportForm').addEventListener('submit', function(e) {
    const slotFoto = document.getElementById('slot-depan');
    if (!slotFoto || !slotFoto.classList.contains('has-file')) {
        e.preventDefault();
        alert('⚠️ Foto kerusakan harus diunggah sebelum mengirim laporan.');
        return;
    }

    const kgWrap = document.getElementById('kegiatanAkademikWrap');
    if (kgWrap.style.display !== 'none') {
        const terpilih = document.querySelector('input[name="kegiatan_akademik"]:checked');
        if (!terpilih) {
            e.preventDefault();
            alert('⚠️ Silakan pilih apakah sedang berlangsung kegiatan akademik di lokasi tersebut.');
            return;
        }
    }

    const tingkatKerusakan = document.getElementById('tingkatKerusakan');
    if (!tingkatKerusakan.value) {
        e.preventDefault();
        alert('⚠️ Silakan pilih tingkat kerusakan barang.');
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '⏳ Mengirim...';
});

// ── Webcam per slot ──
let webcamStream  = null;
let activeSlotId  = null;
let activeInputId = null;

function bukaKameraSlot(slotId, inputId) {
    activeSlotId  = slotId;
    activeInputId = inputId;
    const modal = document.getElementById('webcamModal');
    const video = document.getElementById('webcamVideo');
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false })
        .then(stream => { webcamStream = stream; video.srcObject = stream; modal.classList.add('open'); })
        .catch(() => {
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                .then(stream => { webcamStream = stream; video.srcObject = stream; modal.classList.add('open'); })
                .catch(() => { alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan di browser.'); });
        });
}

function ambilFotoSlot() {
    const video  = document.getElementById('webcamVideo');
    const canvas = document.getElementById('webcamCanvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
    const slot = document.getElementById(activeSlotId);
    setSlotPreview(slot, dataUrl);
    let hiddenInput = document.getElementById('base64_' + activeInputId);
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.id   = 'base64_' + activeInputId;
        hiddenInput.name = 'base64_' + activeInputId;
        document.getElementById('reportForm').appendChild(hiddenInput);
    }
    hiddenInput.value = dataUrl;
    tutupKameraSlot();
}

function tutupKameraSlot() {
    if (webcamStream) { webcamStream.getTracks().forEach(t => t.stop()); webcamStream = null; }
    document.getElementById('webcamModal').classList.remove('open');
    activeSlotId = activeInputId = null;
}

// ── Mode isi manual (barang belum punya kode / belum ada di Master Data) ──
function aktifkanManual(on) {
    document.getElementById('inputManual').checked = on;

    const sel     = document.getElementById('namaBarang');
    const selWrap = document.getElementById('namaBarangSelectWrap');
    const txt     = document.getElementById('namaBarangManual');
    const jenisM  = document.getElementById('jenisManual');
    const kode    = document.getElementById('kodeBarang');
    const hidden  = document.getElementById('jenisBarangHidden');

    if (on) {
        selWrap.style.display = 'none';
        sel.disabled = true;                 // yang dikirim adalah input teks, bukan dropdown
        txt.style.display = 'block';
        txt.disabled = false;
        txt.required = true;
        jenisM.required = true;
        document.getElementById('manualWrap').style.display = 'block';
        document.getElementById('btnCariKode').style.display = 'none';
        document.getElementById('hasilCariKode').textContent = '';
        kode.required = false;               // kode opsional untuk barang manual
        document.getElementById('fotoKodeWrap').style.display = 'none';
        hidden.value = jenisM.value || '';
    } else {
        selWrap.style.display = '';
        sel.disabled = false;
        txt.style.display = 'none';
        txt.disabled = true;
        txt.required = false;
        txt.value = '';
        jenisM.required = false;
        jenisM.value = '';
        document.getElementById('manualWrap').style.display = 'none';
        document.getElementById('btnCariKode').style.display = '';
        const keep = kode.value;
        handleNamaBarang();                  // pulihkan kondisi sesuai dropdown
        if (!sel.value) kode.value = keep;
    }
}

// ── Cari nama alat dari Master Data berdasarkan kode ──
const URL_CARI_KODE = "{{ url('laporan/cari-kode') }}";

function tampilHasil(teks, tipe) {
    const el = document.getElementById('hasilCariKode');
    const warna = { ok: '#166534', warn: '#92400e', err: '#991b1b', info: '#6b7280' };
    el.style.color = warna[tipe] || warna.info;
    el.textContent = teks;
}

function pakaiBarangMaster(item) {
    const sel = document.getElementById('namaBarang');
    let opt = Array.from(sel.options).find(o => o.value === item.nama_barang);
    if (!opt) { opt = new Option(item.nama_barang, item.nama_barang); sel.add(opt); }
    sel.value = item.nama_barang;

    // Barang yang punya kode di Master Data diperlakukan sebagai barang tetap
    document.getElementById('jenisBarangHidden').value = 'elektronik';
    document.getElementById('fotoKodeWrap').style.display = 'block';
    document.getElementById('kodeBarang').required = true;

    tampilHasil('✓ Ditemukan di Master Data: ' + item.nama_barang + (item.merk ? ' — ' + item.merk : ''), 'ok');
}

async function cariKode() {
    if (document.getElementById('inputManual').checked) return;
    const kode = document.getElementById('kodeBarang').value.trim();
    if (!kode) { tampilHasil('Ketik kode barang dulu, lalu klik Cari.', 'warn'); return; }

    const btn = document.getElementById('btnCariKode');
    btn.disabled = true;
    tampilHasil('Mencari...', 'info');

    try {
        const res = await fetch(URL_CARI_KODE + '?kode=' + encodeURIComponent(kode), {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();

        if (!data.items || data.items.length === 0) {
            const el = document.getElementById('hasilCariKode');
            el.style.color = '#92400e';
            el.textContent = 'Kode tidak ditemukan di Master Data. ';
            const b = document.createElement('button');
            b.type = 'button';
            b.textContent = 'Isi data barang secara manual';
            b.style.cssText = 'color:#0f6b3e; font-weight:700; text-decoration:underline; background:none; border:none; cursor:pointer; padding:0; font-size:0.78rem;';
            b.onclick = () => aktifkanManual(true);
            el.appendChild(b);
        } else if (data.items.length === 1) {
            pakaiBarangMaster(data.items[0]);
        } else {
            // Kode dipakai oleh lebih dari satu jenis barang: biarkan pelapor memilih
            const el = document.getElementById('hasilCariKode');
            el.style.color = '#374151';
            el.textContent = 'Kode ini cocok dengan beberapa barang, pilih yang sesuai:';
            data.items.forEach(item => {
                const b = document.createElement('button');
                b.type = 'button';
                b.textContent = item.nama_barang + (item.merk ? ' — ' + item.merk : '');
                b.style.cssText = 'display:block; width:100%; text-align:left; margin-top:6px; padding:8px 12px; ' +
                                  'border:1.5px solid #e5e7eb; border-radius:8px; background:#f9fafb; cursor:pointer; font-size:0.8rem;';
                b.onclick = () => pakaiBarangMaster(item);
                el.appendChild(b);
            });
        }
    } catch (err) {
        tampilHasil('Gagal mencari kode. Coba lagi, atau pilih nama barang secara manual.', 'err');
    } finally {
        btn.disabled = false;
    }
}

// ── Restore state saat old() ada ──
document.addEventListener('DOMContentLoaded', function() {
    const oldTempat = '{{ old("tempat_utama") }}';
    if (oldTempat) {
        handleTempatChange();
    }

    const oldNama   = @json(old('nama_barang'));
    const oldManual = @json((bool) old('input_manual'));
    if (oldManual) {
        aktifkanManual(true);
        document.getElementById('namaBarangManual').value = oldNama || '';
        const oj = @json(old('jenis_barang'));
        if (oj) {
            document.getElementById('jenisManual').value = oj;
            document.getElementById('jenisBarangHidden').value = oj;
        }
    } else if (oldNama) {
        const sel = document.getElementById('namaBarang');
        const ada = Array.from(sel.options).some(o => o.value === oldNama);
        if (ada) {
            handleNamaBarang();
        } else {
            // nama barang berasal dari Master Data (bukan dari daftar bawaan)
            sel.add(new Option(oldNama, oldNama, true, true));
            if (@json(old('jenis_barang')) === 'elektronik') {
                document.getElementById('fotoKodeWrap').style.display = 'block';
                document.getElementById('kodeBarang').required = true;
            }
        }
    }
});
</script>
@endpush