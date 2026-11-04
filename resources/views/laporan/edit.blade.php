@extends('layouts.app')

@section('title', 'Edit Laporan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
    --green-dark: #0a4a2e;
    --green-mid: #0f6b3e;
    --green-bright: #16a34a;
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
.anim-breadcrumb { animation: fadeSlideUp .5s ease both; animation-delay: .05s; }
.anim-title      { animation: fadeSlideUp .55s ease both; animation-delay: .18s; }
.anim-subtitle   { animation: fadeSlideUp .55s ease both; animation-delay: .30s; }
.anim-infobox    { animation: fadeSlideUp .5s ease both; animation-delay: .55s; }
.anim-formcard   { animation: fadeSlideUp .6s ease both; animation-delay: .65s; }

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

.page-body { max-width: 900px; margin: 0 auto; padding: 32px 20px 60px; }

.info-box {
    background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(217,119,6,0.04) 100%);
    border: 1px solid rgba(245,158,11,0.25);
    border-radius: var(--radius-sm); padding: 12px 16px;
    display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px;
}
.info-box p { font-size: 0.8rem; color: #92400e; line-height: 1.5; margin: 0; }

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
.char-count { text-align: right; font-size: 0.73rem; color: var(--gray-400); margin-top: 3px; }
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

.photo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 4px; }
.photo-slot {
    aspect-ratio: 1; border: 2px dashed var(--gray-200);
    border-radius: var(--radius-sm); display: flex; flex-direction: column;
    align-items: center; justify-content: center; cursor: pointer;
    transition: all .2s; background: var(--gray-50);
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

@media (max-width: 600px) {
    .hero-banner { padding: 80px 16px 28px; }
    .hero-content h1 { font-size: 1.35rem; }
    .page-body { padding: 20px 12px 50px; }
    .form-card-body { padding: 18px; }
    .form-row { grid-template-columns: 1fr; }
    .photo-grid { grid-template-columns: repeat(2, 1fr); }
    .form-actions { flex-direction: column; gap: 12px; }
    .btn-back-custom, .btn-submit-custom { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')

<div class="hero-banner">
    <div class="breadcrumb-custom anim-breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="sep">›</span>
        <a href="{{ route('laporan.saya') }}">Laporan Saya</a>
        <span class="sep">›</span>
        <span class="current">Edit Laporan</span>
    </div>
    <div class="hero-content">
        <h1 class="anim-title">Edit <span>Laporan</span> Kerusakan</h1>
        <p class="anim-subtitle">Perbarui informasi laporan kerusakan fasilitas kamu.</p>
    </div>
</div>

<div class="page-body">

    <div class="info-box anim-infobox">
        <span style="font-size:1rem;flex-shrink:0;margin-top:1px;">⚠️</span>
        <p>Foto yang tidak diganti akan tetap menggunakan foto lama. Hanya upload foto baru jika ingin mengganti.</p>
    </div>

    <div class="form-card anim-formcard">
        <div class="form-card-header">
            <div class="form-card-icon">✏️</div>
            <div>
                <h2>Edit Laporan — {{ $laporan->nomor_tiket }}</h2>
                <p>Fakultas Sains & Teknologi — UIN Sunan Ampel Surabaya</p>
            </div>
        </div>

        <form action="{{ route('laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-card-body">

                <div class="form-row">
                    <div class="field">
                        <label>Nama Barang <span class="req">*</span></label>
                        <input type="text" name="nama_barang" id="namaBarang"
                               class="form-control-custom" maxlength="30"
                               placeholder="cth: AC Split 1.5 PK"
                               value="{{ old('nama_barang', $laporan->nama_barang) }}"
                               oninput="updateChar('namaBarang','namaCount',30)">
                        <div class="char-count" id="namaCount">{{ strlen(old('nama_barang', $laporan->nama_barang)) }} / 30</div>
                    </div>

                    <div class="field">
                        <label>Jenis Barang <span class="req">*</span></label>
                        <div class="select-wrap">
                            <select name="jenis_barang" class="form-select-custom">
                                <option value="" disabled>— Pilih jenis barang —</option>
                                <option value="elektronik" {{ old('jenis_barang', $laporan->jenis_barang) == 'elektronik' ? 'selected' : '' }}>⚡ Elektronik</option>
                                <option value="non-elektronik" {{ old('jenis_barang', $laporan->jenis_barang) == 'non-elektronik' ? 'selected' : '' }}>🪑 Non-Elektronik</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Pilihan Tempat <span class="req">*</span></label>
                    <div class="place-selector">
                        <div class="select-wrap">
                            <select name="tempat_utama" id="tempatUtama" class="form-select-custom" onchange="handleTempatChange()">
                                <option value="" disabled>— Pilih lokasi utama —</option>
                                <optgroup label="Laboratorium">
                                    <option value="lab_si" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_si' ? 'selected' : '' }}>Laboratorium Sistem Informasi</option>
                                    <option value="lab_arsi" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_arsi' ? 'selected' : '' }}>Laboratorium Arsitektur</option>
                                    <option value="lab_kelautan" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_kelautan' ? 'selected' : '' }}>Laboratorium Ilmu Kelautan</option>
                                    <option value="lab_sipil" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_sipil' ? 'selected' : '' }}>Laboratorium Teknik Sipil</option>
                                    <option value="lab_bio" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_bio' ? 'selected' : '' }}>Laboratorium Biologi</option>
                                    <option value="lab_matematika" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_matematika' ? 'selected' : '' }}>Laboratorium Matematika</option>
                                    <option value="lab_tl" {{ old('tempat_utama', $laporan->tempat_utama) == 'lab_tl' ? 'selected' : '' }}>Laboratorium Teknik Lingkungan</option>
                                </optgroup>
                                <optgroup label="Ruang Umum">
                                    <option value="ruang_ta" {{ old('tempat_utama', $laporan->tempat_utama) == 'ruang_ta' ? 'selected' : '' }}>Ruang TA / Serbaguna</option>
                                    <option value="ruang_kelas_6" {{ old('tempat_utama', $laporan->tempat_utama) == 'ruang_kelas_6' ? 'selected' : '' }}>Ruang Kelas Lantai 6</option>
                                    <option value="ruang_kelas_7" {{ old('tempat_utama', $laporan->tempat_utama) == 'ruang_kelas_7' ? 'selected' : '' }}>Ruang Kelas Lantai 7</option>
                                    <option value="ruang_kelas_8" {{ old('tempat_utama', $laporan->tempat_utama) == 'ruang_kelas_8' ? 'selected' : '' }}>Ruang Kelas Lantai 8</option>
                                    <option value="ruang_kelas_9" {{ old('tempat_utama', $laporan->tempat_utama) == 'ruang_kelas_9' ? 'selected' : '' }}>Ruang Kelas Lantai 9</option>
                                    <option value="akademik" {{ old('tempat_utama', $laporan->tempat_utama) == 'akademik' ? 'selected' : '' }}>Akademik / Ruang Fakultas</option>
                                    <option value="podcast" {{ old('tempat_utama', $laporan->tempat_utama) == 'podcast' ? 'selected' : '' }}>Podcast</option>
                                </optgroup>
                                <optgroup label="Fasilitas Umum">
                                    <option value="toilet_lk" {{ old('tempat_utama', $laporan->tempat_utama) == 'toilet_lk' ? 'selected' : '' }}>Toilet Laki-laki</option>
                                    <option value="toilet_pr" {{ old('tempat_utama', $laporan->tempat_utama) == 'toilet_pr' ? 'selected' : '' }}>Toilet Perempuan</option>
                                    <option value="musholla_7" {{ old('tempat_utama', $laporan->tempat_utama) == 'musholla_7' ? 'selected' : '' }}>Musholla Lantai 7</option>
                                    <option value="musholla_8" {{ old('tempat_utama', $laporan->tempat_utama) == 'musholla_8' ? 'selected' : '' }}>Musholla Lantai 8</option>
                                    <option value="musholla_9" {{ old('tempat_utama', $laporan->tempat_utama) == 'musholla_9' ? 'selected' : '' }}>Musholla Lantai 9</option>
                                </optgroup>
                                <optgroup label="Ruang Program Studi">
                                    <option value="prodi_si" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_si' ? 'selected' : '' }}>Ruang Prodi Sistem Informasi</option>
                                    <option value="prodi_arsi" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_arsi' ? 'selected' : '' }}>Ruang Prodi Arsitektur</option>
                                    <option value="prodi_bio" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_bio' ? 'selected' : '' }}>Ruang Prodi Biologi</option>
                                    <option value="prodi_mat" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_mat' ? 'selected' : '' }}>Ruang Prodi Matematika</option>
                                    <option value="prodi_sipil" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_sipil' ? 'selected' : '' }}>Ruang Prodi Teknik Sipil</option>
                                    <option value="prodi_tl" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_tl' ? 'selected' : '' }}>Ruang Prodi Teknik Lingkungan</option>
                                    <option value="prodi_kelautan" {{ old('tempat_utama', $laporan->tempat_utama) == 'prodi_kelautan' ? 'selected' : '' }}>Ruang Prodi Ilmu Kelautan</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="select-wrap" id="subLokasiWrap" style="display:none;">
                            <select name="sub_lokasi" id="subLokasi" class="form-select-custom">
                                <option value="" disabled selected>— Pilih sub-lokasi —</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-divider"></div>

                <div class="field">
                    <label style="margin-bottom:4px;">Foto Kerusakan (6 Sisi) — Kosongkan jika tidak ingin mengganti</label>
                    <div class="photo-grid">
                        @foreach([
                            ['id' => 'foto_atas',     'slot' => 'slot-atas',     'icon' => '🔼', 'label' => 'Atas'],
                            ['id' => 'foto_bawah',    'slot' => 'slot-bawah',    'icon' => '🔽', 'label' => 'Bawah'],
                            ['id' => 'foto_kanan',    'slot' => 'slot-kanan',    'icon' => '▶️',  'label' => 'Kanan'],
                            ['id' => 'foto_kiri',     'slot' => 'slot-kiri',     'icon' => '◀️',  'label' => 'Kiri'],
                            ['id' => 'foto_depan',    'slot' => 'slot-depan',    'icon' => '⏩', 'label' => 'Depan'],
                            ['id' => 'foto_belakang', 'slot' => 'slot-belakang', 'icon' => '⏪', 'label' => 'Belakang'],
                        ] as $foto)
                        @php $existingFoto = $laporan->{$foto['id']}; @endphp
                        <label for="{{ $foto['id'] }}" class="photo-slot {{ $existingFoto ? 'has-file' : '' }}" id="{{ $foto['slot'] }}">
                            <input type="file" name="{{ $foto['id'] }}" id="{{ $foto['id'] }}"
                                   accept="image/*" onchange="handlePhoto(this,'{{ $foto['slot'] }}')">
                            @if($existingFoto)
                                <img src="{{ asset('storage/' . $existingFoto) }}" class="preview-img">
                            @endif
                            <span class="slot-icon" style="{{ $existingFoto ? 'opacity:0' : '' }}">{{ $foto['icon'] }}</span>
                            <span class="slot-label" style="{{ $existingFoto ? 'opacity:0' : '' }}">{{ $foto['label'] }}</span>
                            <span class="slot-hint" style="{{ $existingFoto ? 'opacity:0' : '' }}">Klik untuk ganti</span>
                            <div class="check-badge">✓</div>
                            <button type="button" class="delete-btn"
                                    onclick="hapusFoto(event,'{{ $foto['slot'] }}','{{ $foto['id'] }}')">✕</button>
                            <div class="preview-overlay">🔄 Ganti Foto</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-divider"></div>

                <div class="field">
                    <label>Deskripsi Kerusakan <span class="req">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control-custom"
                              maxlength="250" rows="4"
                              placeholder="Jelaskan kerusakan secara detail..."
                              oninput="updateChar('deskripsi','deskCount',250)">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                    <div class="char-count" id="deskCount">{{ strlen(old('deskripsi', $laporan->deskripsi)) }} / 250</div>
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('laporan.saya') }}" class="btn-back-custom">← Batal</a>
                <button type="submit" class="btn-submit-custom">Simpan Perubahan →</button>
            </div>

        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
const subLocs = {
    lab_si: ['Intelligent Systems Laboratory','Database Laboratory','IT Governance Laboratory','OS & Networking Laboratory','Programming Laboratory','Research Laboratory'],
    lab_arsi: ['Studio Perancangan Arsitektur','Studio Gambar / Komputasi','Ruang Maket'],
    lab_kelautan: ['Laboratorium Biokimia','Laboratorium Bioteknologi Laut','Laboratorium Survey Hidro-oseanografi'],
    lab_sipil: ['Civil Engineering Computer Laboratory','Concrete and Materials Laboratory','Hydrologi-Hydraulics Laboratory','Mapping and Risk Mitigation Laboratory','Soil and Geohydrology Laboratory','Studio and Design Laboratory','Structure Laboratory','Transportation Laboratory'],
    lab_bio: ['Genetic and Biomolecular Laboratory','Microbiology Laboratory','Plant Ecophysiology Laboratory','Animal Physiology Laboratory','Plant Cell and Tissue Culture Laboratory','Immunology, Cell and Tissue Culture Laboratory','Ecology & Bioconservation Laboratory','Biosystematic Laboratory'],
    lab_matematika: ['Modelling and Simulation Laboratory','Intelligent Computation and Vision Laboratory','Falaq Science','Operational Research Laboratory','Statistics Laboratory','Algebra and Analysis Laboratory'],
    lab_tl: ['Solid and Hazardous Waste Laboratory','Mapping and Modelling Laboratory','Sanitation and Remediation Laboratory','Environmental Chemistry Laboratory','Air Quality and Noise Laboratory','Environmental Workshop Laboratory','Water Quality and Processing Laboratory','Microbiology and Biotechnology Laboratory'],
    ruang_ta: ['Ruang TA Lantai 1 Utara','Ruang TA/Serbaguna Lantai 2 Utara','Ruang TA/Serbaguna Lantai 2 Selatan','Ruang TA/Serbaguna Lantai 3 Utara','Ruang TA/Serbaguna Lantai 3 Selatan','Ruang TA/Serbaguna Lantai 4 Utara','Ruang TA/Serbaguna Lantai 4 Selatan','Ruang TA/Serbaguna Lantai 5 Utara','Ruang TA/Serbaguna Lantai 5 Tengah','Ruang TA/Serbaguna Lantai 5 Selatan'],
    ruang_kelas_6: ['Ruang Kelas 6.1','Ruang Kelas 6.2','Ruang Kelas 6.3','Ruang Kelas 6.4','Ruang Kelas 6.5','Ruang Kelas 6.6','Ruang Kelas 6.7','Ruang Kelas 6.8','Ruang Kelas 6.9','Ruang Kelas 6.10','Ruang Kelas 6.11'],
    ruang_kelas_7: ['Ruang Kelas 7.1','Ruang Kelas 7.2','Ruang Kelas 7.3','Ruang Kelas 7.4','Ruang Kelas 7.5','Ruang Kelas 7.6','Ruang Kelas 7.7','Ruang Kelas 7.8','Ruang Kelas 7.9','Ruang Kelas 7.10','Ruang Kelas 7.11'],
    ruang_kelas_8: ['Ruang Kelas 8.1','Ruang Kelas 8.2','Ruang Kelas 8.3','Ruang Kelas 8.4','Ruang Kelas 8.5','Ruang Kelas 8.6','Ruang Kelas 8.7','Ruang Kelas 8.8','Ruang Kelas 8.9','Ruang Kelas 8.10','Ruang Kelas 8.11'],
    ruang_kelas_9: ['Ruang Kelas 9.1','Ruang Kelas 9.2','Ruang Kelas 9.3','Ruang Kelas 9.4','Ruang Kelas 9.5','Ruang Kelas 9.6','Ruang Kelas 9.7','Ruang Kelas 9.8','Ruang Kelas 9.9','Ruang Kelas 9.10','Ruang Kelas 9.11'],
    toilet_lk: ['Lantai 6','Lantai 7','Lantai 8','Lantai 9'],
    toilet_pr: ['Lantai 6','Lantai 7','Lantai 8','Lantai 9'],
};

function handleTempatChange() {
    const val = document.getElementById('tempatUtama').value;
    const subWrap = document.getElementById('subLokasiWrap');
    const subSelect = document.getElementById('subLokasi');
    subWrap.style.display = 'none';
    if (subLocs[val]) {
        subSelect.innerHTML = '<option value="" disabled selected>— Pilih sub-lokasi —</option>';
        subLocs[val].forEach(name => {
            const opt = document.createElement('option');
            opt.value = name.toLowerCase().replace(/[\s\/]+/g, '_');
            opt.textContent = name;
            subSelect.appendChild(opt);
        });
        subWrap.style.display = 'block';
    }
}

function updateChar(inputId, countId, max) {
    const el = document.getElementById(inputId);
    const counter = document.getElementById(countId);
    const len = el.value.length;
    counter.textContent = len + ' / ' + max;
    counter.className = 'char-count';
    if (len > max * 0.85) counter.classList.add('warn');
    if (len >= max) counter.classList.add('over');
}

function handlePhoto(input, slotId) {
    if (!input.files || !input.files[0]) return;
    const slot = document.getElementById(slotId);
    const reader = new FileReader();
    reader.onload = (e) => {
        const oldImg = slot.querySelector('.preview-img');
        if (oldImg) oldImg.remove();
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'preview-img';
        slot.insertBefore(img, slot.firstChild);
        slot.classList.add('has-file');
        slot.querySelectorAll('.slot-icon, .slot-label, .slot-hint').forEach(el => el.style.opacity = '0');
    };
    reader.readAsDataURL(input.files[0]);
}

function hapusFoto(event, slotId, inputId) {
    event.preventDefault();
    event.stopPropagation();
    const slot = document.getElementById(slotId);
    const input = document.getElementById(inputId);
    const oldImg = slot.querySelector('.preview-img');
    if (oldImg) oldImg.remove();
    input.value = '';
    slot.classList.remove('has-file');
    slot.querySelectorAll('.slot-icon, .slot-label, .slot-hint').forEach(el => el.style.opacity = '1');
}

document.addEventListener('DOMContentLoaded', function() {
    const oldTempat = '{{ $laporan->tempat_utama }}';
    if (oldTempat) handleTempatChange();
});
</script>
@endpush