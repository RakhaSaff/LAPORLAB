{{-- resources/views/admin/import/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Import Laporan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Import Laporan</h1>
        <p class="page-subtitle">Import data laporan dari file Excel atau CSV</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.import.template') }}" class="btn btn-outline">
            <i class="ti ti-download"></i> Download Template
        </a>
    </div>
</div>

@if(session('import_result'))
    @php $result = session('import_result'); @endphp
    <div class="alert alert-success">
        <i class="ti ti-circle-check"></i>
        <div>
            <strong>Import selesai!</strong>
            {{ $result['berhasil'] }} laporan berhasil diimport.
            @if($result['gagal'] > 0)
                <span class="text-danger">{{ $result['gagal'] }} baris gagal.</span>
            @endif
        </div>
    </div>
    @if(!empty($result['errors']))
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle"></i>
            <div>
                <strong>Detail error:</strong>
                <ul class="error-list">
                    @foreach($result['errors'] as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endif

@if(session('error'))
    <div class="alert alert-danger"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
@endif

@if(session('info'))
    <div class="alert alert-info"><i class="ti ti-info-circle"></i> {{ session('info') }}</div>
@endif

<div class="import-grid">

    {{-- Upload Card --}}
    <div class="card card-upload">
        <div class="card-header">
            <h2 class="card-title"><i class="ti ti-file-upload"></i> Upload File</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.import.preview') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="dropzone" id="dropzone">
                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" hidden>
                    <div class="dropzone-content" id="dropzoneContent">
                        <div class="dropzone-icon"><i class="ti ti-cloud-upload"></i></div>
                        <p class="dropzone-text">Drag & drop file di sini atau</p>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">Pilih File</button>
                        <p class="dropzone-hint">Excel (.xlsx/.xls) atau CSV • Maks 10MB</p>
                    </div>
                    <div class="dropzone-selected" id="dropzoneSelected" style="display:none">
                        <div class="file-info">
                            <i class="ti ti-file-check file-icon" id="fileIcon"></i>
                            <div>
                                <p class="file-name" id="fileName">—</p>
                                <p class="file-size" id="fileSize">—</p>
                            </div>
                            <button type="button" class="btn-remove" onclick="resetFile()"><i class="ti ti-x"></i></button>
                        </div>
                    </div>
                </div>
                @error('file')
                    <p class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>
                    <i class="ti ti-eye"></i> Preview & Lanjutkan
                </button>
            </form>
        </div>
    </div>

    {{-- Format yang Didukung --}}
    <div class="card card-formats">
        <div class="card-header">
            <h2 class="card-title"><i class="ti ti-file-description"></i> Format yang Didukung</h2>
        </div>
        <div class="card-body">
            <div class="format-list">
                <div class="format-item">
                    <div class="format-icon format-excel"><i class="ti ti-file-spreadsheet"></i></div>
                    <div>
                        <p class="format-name">Excel</p>
                        <p class="format-ext">.xlsx / .xls</p>
                        <p class="format-desc">Format paling direkomendasikan. Header di baris pertama.</p>
                    </div>
                </div>
                <div class="format-item">
                    <div class="format-icon format-csv"><i class="ti ti-table"></i></div>
                    <div>
                        <p class="format-name">CSV</p>
                        <p class="format-ext">.csv</p>
                        <p class="format-desc">Comma atau semicolon separated. Header di baris pertama.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom yang Dikenali --}}
    <div class="card card-columns">
        <div class="card-header">
            <h2 class="card-title"><i class="ti ti-columns"></i> Kolom yang Dikenali</h2>
            <p class="card-subtitle">Sistem akan otomatis mendeteksi kolom dari berbagai format</p>
        </div>
        <div class="card-body">
            <div class="columns-grid">
                <div class="column-group">
                    <p class="column-group-title">Nama Barang</p>
                    <div class="tags">
                        <span class="tag">nama_barang</span><span class="tag">barang</span>
                        <span class="tag">asset</span><span class="tag">fasilitas</span>
                    </div>
                </div>
                <div class="column-group">
                    <p class="column-group-title">Lokasi</p>
                    <div class="tags">
                        <span class="tag">lokasi</span><span class="tag">tempat_utama</span>
                        <span class="tag">ruangan</span><span class="tag">gedung</span>
                    </div>
                </div>
                <div class="column-group">
                    <p class="column-group-title">Jenis Barang</p>
                    <div class="tags">
                        <span class="tag">jenis_barang</span><span class="tag">jenis</span>
                        <span class="tag">kategori</span><span class="tag">tipe</span>
                    </div>
                </div>
                <div class="column-group">
                    <p class="column-group-title">Deskripsi</p>
                    <div class="tags">
                        <span class="tag">deskripsi</span><span class="tag">keterangan</span>
                        <span class="tag">keluhan</span><span class="tag">uraian_kerusakan</span>
                    </div>
                </div>
                <div class="column-group">
                    <p class="column-group-title">Tingkat Kerusakan</p>
                    <div class="tags">
                        <span class="tag">tingkat_kerusakan</span><span class="tag">prioritas</span>
                        <span class="tag">severity</span><span class="tag">level</span>
                    </div>
                </div>
                <div class="column-group">
                    <p class="column-group-title">Pelapor</p>
                    <div class="tags">
                        <span class="tag">pelapor</span><span class="tag">email</span>
                        <span class="tag">nama_pelapor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.page-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem}
.page-title{font-size:1.5rem;font-weight:600;color:#1a1a1a;margin:0}
.page-subtitle{color:#6b7280;font-size:.875rem;margin:.25rem 0 0}
.import-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.card-upload{grid-column:1}.card-formats{grid-column:2}.card-columns{grid-column:1/-1}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.card-header{padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6}
.card-title{font-size:.9375rem;font-weight:600;color:#1a1a1a;margin:0;display:flex;align-items:center;gap:.5rem}
.card-subtitle{font-size:.8125rem;color:#9ca3af;margin:.25rem 0 0}
.card-body{padding:1.25rem}
.dropzone{border:2px dashed #d1d5db;border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:all .2s;background:#f9fafb}
.dropzone.dragover{border-color:#16a34a;background:#f0fdf4}
.dropzone-icon{font-size:2.5rem;color:#9ca3af;margin-bottom:.75rem}
.dropzone-text{color:#6b7280;font-size:.875rem;margin:0 0 .75rem}
.dropzone-hint{color:#9ca3af;font-size:.75rem;margin:.75rem 0 0}
.file-info{display:flex;align-items:center;gap:.75rem;text-align:left}
.file-icon{font-size:2rem;color:#16a34a}
.file-name{font-size:.875rem;font-weight:500;color:#1a1a1a;margin:0}
.file-size{font-size:.75rem;color:#9ca3af;margin:.125rem 0 0}
.btn-remove{margin-left:auto;background:none;border:none;color:#9ca3af;cursor:pointer;padding:.25rem;border-radius:4px;display:flex}
.btn-remove:hover{color:#ef4444;background:#fef2f2}
.btn{display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1rem;border-radius:8px;font-size:.875rem;font-weight:500;cursor:pointer;border:1px solid transparent;transition:all .15s;text-decoration:none}
.btn-primary{background:#16a34a;color:#fff;border-color:#16a34a}
.btn-primary:hover{background:#15803d}
.btn-primary:disabled{background:#d1d5db;border-color:#d1d5db;cursor:not-allowed}
.btn-outline{background:#fff;color:#374151;border-color:#d1d5db}
.btn-outline:hover{background:#f9fafb}
.btn-block{width:100%;justify-content:center;margin-top:1rem}
.format-list{display:flex;flex-direction:column;gap:1rem}
.format-item{display:flex;align-items:flex-start;gap:.75rem}
.format-icon{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
.format-excel{background:#dcfce7;color:#16a34a}
.format-csv{background:#dbeafe;color:#2563eb}
.format-name{font-size:.875rem;font-weight:600;color:#1a1a1a;margin:0}
.format-ext{font-size:.75rem;color:#6b7280;margin:.1rem 0}
.format-desc{font-size:.75rem;color:#9ca3af;margin:0}
.columns-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.column-group-title{font-size:.8125rem;font-weight:600;color:#374151;margin:0 0 .5rem}
.tags{display:flex;flex-wrap:wrap;gap:.375rem}
.tag{background:#f3f4f6;color:#374151;font-size:.6875rem;padding:.2rem .5rem;border-radius:4px;font-family:monospace}
.alert{display:flex;align-items:flex-start;gap:.75rem;padding:.875rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.875rem}
.alert-success{background:#dcfce7;color:#15803d}
.alert-warning{background:#fef9c3;color:#854d0e}
.alert-danger{background:#fee2e2;color:#dc2626}
.alert-info{background:#dbeafe;color:#1d4ed8}
.error-list{margin:.25rem 0 0;padding-left:1.25rem}
.field-error{color:#dc2626;font-size:.8125rem;margin:.375rem 0 0;display:flex;align-items:center;gap:.25rem}
</style>
@endpush

@push('scripts')
<script>
const fileInput  = document.getElementById('fileInput');
const dropzone   = document.getElementById('dropzone');
const dzContent  = document.getElementById('dropzoneContent');
const dzSelected = document.getElementById('dropzoneSelected');
const fileName   = document.getElementById('fileName');
const fileSize   = document.getElementById('fileSize');
const fileIcon   = document.getElementById('fileIcon');
const submitBtn  = document.getElementById('submitBtn');
const iconMap    = {xlsx:'ti-file-spreadsheet',xls:'ti-file-spreadsheet',csv:'ti-table'};

fileInput.addEventListener('change', () => handleFile(fileInput.files[0]));
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
dropzone.addEventListener('drop', e => {
    e.preventDefault(); dropzone.classList.remove('dragover');
    if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
});

function handleFile(file) {
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    fileName.textContent = file.name;
    fileSize.textContent = (file.size/1024).toFixed(1) + ' KB';
    fileIcon.className   = 'ti ' + (iconMap[ext]||'ti-file') + ' file-icon';
    dzContent.style.display  = 'none';
    dzSelected.style.display = 'block';
    submitBtn.disabled = false;
    const dt = new DataTransfer(); dt.items.add(file); fileInput.files = dt.files;
}

function resetFile() {
    fileInput.value = '';
    dzContent.style.display  = 'block';
    dzSelected.style.display = 'none';
    submitBtn.disabled = true;
}
</script>
@endpush