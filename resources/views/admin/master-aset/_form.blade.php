{{-- resources/views/admin/master-aset/_form.blade.php --}}
{{-- Dipakai bareng oleh create.blade.php & edit.blade.php --}}

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    {{-- Kode Barang: Auto-generate, readonly --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Kode Barang
            <span class="ml-1 text-xs font-normal text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">otomatis</span>
        </label>
        <div class="relative">
            <input type="text" name="kode_barang" id="kode_barang"
                   value="{{ old('kode_barang', $aset->kode_barang ?? '') }}"
                   readonly
                   placeholder="Akan digenerate otomatis..."
                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none">
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
        </div>
        <p class="text-xs text-gray-400 mt-1">Kode digenerate otomatis berdasarkan jenis & urutan aset</p>
        @error('kode_barang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Nama Barang --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Nama Barang <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nama_barang"
               value="{{ old('nama_barang', $aset->nama_barang ?? '') }}"
               required placeholder="Misal: Proyektor Epson"
               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
        @error('nama_barang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

    {{-- Jenis Aset --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Jenis Aset <span class="text-red-500">*</span>
        </label>
        @php $jenisAsetOld = old('jenis_aset', $aset->jenis_aset ?? ''); @endphp
        <select name="jenis_aset" id="jenis_aset" required
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 bg-white">
            <option value="" disabled {{ $jenisAsetOld === '' ? 'selected' : '' }}>Pilih jenis aset</option>
            <option value="barang_tetap"       {{ $jenisAsetOld === 'barang_tetap'       ? 'selected' : '' }}>Barang Tetap</option>
            <option value="barang_tidak_tetap" {{ $jenisAsetOld === 'barang_tidak_tetap' ? 'selected' : '' }}>Barang Tidak Tetap</option>
        </select>
        @error('jenis_aset')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Kategori --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
        <input type="text" name="kategori"
               value="{{ old('kategori', $aset->kategori ?? '') }}"
               placeholder="Opsional, misal Elektronik"
               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
        @error('kategori')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Lokasi Utama + Sub Lokasi --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi Utama</label>
        @php
            $lokasiGroups = [
                'Laboratorium' => [
                    'lab_si'         => 'Laboratorium Sistem Informasi',
                    'lab_arsi'       => 'Laboratorium Arsitektur',
                    'lab_kelautan'   => 'Laboratorium Ilmu Kelautan',
                    'lab_sipil'      => 'Laboratorium Teknik Sipil',
                    'lab_bio'        => 'Laboratorium Biologi',
                    'lab_matematika' => 'Laboratorium Matematika',
                    'lab_tl'         => 'Laboratorium Teknik Lingkungan',
                ],
                'Ruang Kelas' => [
                    'ruang_kelas_6'  => 'Ruang Kelas Lantai 6',
                    'ruang_kelas_7'  => 'Ruang Kelas Lantai 7',
                    'ruang_kelas_8'  => 'Ruang Kelas Lantai 8',
                    'ruang_kelas_9'  => 'Ruang Kelas Lantai 9',
                ],
                'Ruang Prodi' => [
                    'prodi_si'       => 'Prodi Sistem Informasi',
                    'prodi_arsi'     => 'Prodi Arsitektur',
                    'prodi_bio'      => 'Prodi Biologi',
                    'prodi_mat'      => 'Prodi Matematika',
                    'prodi_sipil'    => 'Prodi Teknik Sipil',
                    'prodi_tl'       => 'Prodi Teknik Lingkungan',
                    'prodi_kelautan' => 'Prodi Ilmu Kelautan',
                ],
                'Fasilitas Umum' => [
                    'ruang_ta'   => 'Ruang TA / Serbaguna',
                    'akademik'   => 'Akademik / Ruang Fakultas',
                    'podcast'    => 'Podcast',
                    'toilet_lk'  => 'Toilet Laki-laki',
                    'toilet_pr'  => 'Toilet Perempuan',
                    'musholla_7' => 'Musholla Lantai 7',
                    'musholla_8' => 'Musholla Lantai 8',
                    'musholla_9' => 'Musholla Lantai 9',
                ],
            ];
            $lokasiOld    = old('lokasi', $aset->lokasi ?? '');
            $subLokasiOld = old('sub_lokasi', $aset->sub_lokasi ?? '');
        @endphp

        <select name="lokasi" id="lokasi_utama"
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 bg-white">
            <option value="">— Pilih Lokasi (Opsional) —</option>
            @foreach($lokasiGroups as $group => $options)
                <optgroup label="{{ $group }}">
                    @foreach($options as $val => $label)
                        <option value="{{ $val }}" {{ $lokasiOld === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        @error('lokasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Sub Lokasi --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sub Lokasi</label>
        <select name="sub_lokasi" id="sub_lokasi"
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 bg-white">
            <option value="">— Pilih sub-lokasi —</option>
        </select>
        @error('sub_lokasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
    {{-- Tahun Perolehan --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Perolehan</label>
        <input type="number" name="tahun_perolehan"
               value="{{ old('tahun_perolehan', $aset->tahun_perolehan ?? '') }}"
               placeholder="Opsional, misal 2023" min="1900" max="{{ date('Y') + 1 }}"
               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400">
        @error('tahun_perolehan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Spesifikasi --}}
<div class="mt-4">
    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Spesifikasi</label>
    <textarea name="spesifikasi" rows="4"
              placeholder="Opsional, detail merk/kondisi/catatan lain"
              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 resize-none">{{ old('spesifikasi', $aset->spesifikasi ?? '') }}</textarea>
    @error('spesifikasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>

@push('scripts')
<script>
// ── Data sub-lokasi lengkap ───────────────────────────────────────────
const subLokasiData = {
    lab_si: [
        'Intelligent Systems Laboratory',
        'Database Laboratory',
        'IT Governance Laboratory',
        'OS & Networking Laboratory',
        'Programming Laboratory',
        'Research Laboratory',
    ],
    lab_arsi: [
        'Studio Perancangan Arsitektur',
        'Studio Gambar / Komputasi',
        'Ruang Maket',
    ],
    lab_kelautan: [
        'Laboratorium Biokimia',
        'Laboratorium Bioteknologi Laut',
        'Laboratorium Survey Hidro-oseanografi',
    ],
    lab_sipil: [
        'Civil Engineering Computer Laboratory',
        'Concrete and Materials Laboratory',
        'Hydrologi-Hydraulics Laboratory',
        'Mapping and Risk Mitigation Laboratory',
        'Soil and Geohydrology Laboratory',
        'Studio and Design Laboratory',
        'Structure Laboratory',
        'Transportation Laboratory',
    ],
    lab_bio: [
        'Genetic and Biomolecular Laboratory',
        'Microbiology Laboratory',
        'Plant Ecophysiology Laboratory',
        'Animal Physiology Laboratory',
        'Plant Cell and Tissue Culture Laboratory',
        'Immunology, Cell and Tissue Culture Laboratory',
        'Ecology & Bioconservation Laboratory',
        'Biosystematic Laboratory',
    ],
    lab_matematika: [
        'Modelling and Simulation Laboratory',
        'Intelligent Computation and Vision Laboratory',
        'Falaq Science',
        'Operational Research Laboratory',
        'Statistics Laboratory',
        'Algebra and Analysis Laboratory',
    ],
    lab_tl: [
        'Solid and Hazardous Waste Laboratory',
        'Mapping and Modelling Laboratory',
        'Sanitation and Remediation Laboratory',
        'Environmental Chemistry Laboratory',
        'Air Quality and Noise Laboratory',
        'Environmental Workshop Laboratory',
        'Water Quality and Processing Laboratory',
        'Microbiology and Biotechnology Laboratory',
    ],
    ruang_kelas_6:  Array.from({length:11}, (_,i) => `Ruang Kelas 6.${i+1}`),
    ruang_kelas_7:  Array.from({length:11}, (_,i) => `Ruang Kelas 7.${i+1}`),
    ruang_kelas_8:  Array.from({length:11}, (_,i) => `Ruang Kelas 8.${i+1}`),
    ruang_kelas_9:  Array.from({length:11}, (_,i) => `Ruang Kelas 9.${i+1}`),
    toilet_lk: ['Toilet Laki-laki Lantai 6','Toilet Laki-laki Lantai 7','Toilet Laki-laki Lantai 8','Toilet Laki-laki Lantai 9'],
    toilet_pr: ['Toilet Perempuan Lantai 6','Toilet Perempuan Lantai 7','Toilet Perempuan Lantai 8','Toilet Perempuan Lantai 9'],
};

const lokasiSelect    = document.getElementById('lokasi_utama');
const subLokasiSelect = document.getElementById('sub_lokasi');
const savedSubLokasi  = "{{ $subLokasiOld }}";

function updateSubLokasi(lokasi, selected = '') {
    subLokasiSelect.innerHTML = '<option value="">— Pilih sub-lokasi —</option>';
    const options = subLokasiData[lokasi] || [];
    options.forEach(opt => {
        const el = document.createElement('option');
        el.value = opt;
        el.textContent = opt;
        if (opt === selected) el.selected = true;
        subLokasiSelect.appendChild(el);
    });
    subLokasiSelect.disabled = options.length === 0;
}

// Init saat halaman load (untuk mode edit)
if (lokasiSelect.value) {
    updateSubLokasi(lokasiSelect.value, savedSubLokasi);
}

lokasiSelect.addEventListener('change', function() {
    updateSubLokasi(this.value);
});

// ── Generate kode otomatis ────────────────────────────────────────────
const jenisSelect = document.getElementById('jenis_aset');
const kodeInput   = document.getElementById('kode_barang');
const isEdit      = {{ isset($aset) && $aset->exists ? 'true' : 'false' }};

if (!isEdit) {
    jenisSelect.addEventListener('change', generateKode);
    if (jenisSelect.value) generateKode();
}

async function generateKode() {
    const jenis = jenisSelect.value;
    if (!jenis) return;
    try {
        const res  = await fetch(`/admin/master-aset/generate-kode?jenis=${jenis}`);
        const data = await res.json();
        kodeInput.value = data.kode;
    } catch (e) {
        const prefix = jenis === 'barang_tetap' ? 'BT' : 'BTT';
        kodeInput.value = `SMTFAS-${prefix}-${new Date().getFullYear()}-${Date.now().toString().slice(-3)}`;
    }
}
</script>
@endpush