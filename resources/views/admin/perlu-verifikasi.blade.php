@extends('layouts.admin')

@section('title', 'Perlu Diverifikasi — LAPORLAB Admin')
@section('page-title', 'Perlu Diverifikasi')
@section('page-subtitle', 'Laporan yang menunggu verifikasi admin')

@section('content')

<div class="card fade-in fade-in-1">
    <div class="card-header">
        <div>
            <p class="card-title">Antrian Verifikasi</p>
            <p class="text-xs text-gray-400 mt-0.5">Verifikasi laporan & tentukan tindak lanjut</p>
        </div>
        <span class="badge badge-sedang text-sm">
            <i class="fa-solid fa-clock"></i> {{ $laporan->total() ?? 0 }} Menunggu
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th># </th>
                    <th>Pelapor</th>
                    <th>Fasilitas / Barang</th>
                    <th>No.Inv</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Tingkat</th>
                    <th>Waktu Upload</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan ?? [] as $item)
                <tr>
                    <td class="text-gray-400 text-xs font-mono">{{ $loop->iteration }}</td>
                    <td>
                        <div class="font-semibold text-sm text-gray-800">{{ $item->user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $item->user->role ?? 'Mahasiswa' }}</div>
                    </td>
                    <td>
                        <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-gray-400">
                            {{ ucwords(str_replace('_', ' ', $item->tempat_label ?? $item->tempat_utama ?? '-')) }}
                        </div>
                    </td>
                    <td class="text-sm text-gray-700 font-mono">
                        {{ $item->kode_barang ?? '-' }}
                    </td>
                    <td class="text-sm text-gray-600 max-w-[200px]">
                        <p class="line-clamp-2">{{ $item->deskripsi }}</p>
                    </td>
                    <td>
                        @php
                            $fotoPreview = $item->foto_atas ?? $item->foto_depan ?? $item->foto_kanan ??
                                           $item->foto_kiri ?? $item->foto_bawah ?? $item->foto_belakang ?? null;
                        @endphp
                        @if($fotoPreview)
                            <img src="{{ asset('storage/' . $fotoPreview) }}"
                                 class="w-14 h-10 object-cover rounded-lg border cursor-pointer hover:opacity-80 transition"
                                 onclick="openPhoto('{{ asset('storage/' . $fotoPreview) }}')">
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-tingkat" id="badge-{{ $item->id }}">
                            @if($item->tingkat_kerusakan === 'tinggi')
                                <span class="badge badge-tinggi"><i class="fa-solid fa-circle-exclamation"></i> Tinggi</span>
                            @elseif($item->tingkat_kerusakan === 'sedang')
                                <span class="badge badge-sedang"><i class="fa-solid fa-triangle-exclamation"></i> Sedang</span>
                            @else
                                <span class="badge badge-rendah"><i class="fa-solid fa-circle-info"></i> Rendah</span>
                            @endif
                        </span>
                    </td>
                    <td class="text-xs text-gray-500">
                        <div>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.perlu-verifikasi.proses', $item->id) }}"
                              class="flex flex-col gap-2 min-w-[150px]">
                            @csrf @method('PATCH')
                            <select name="tingkat_kerusakan"
                                    onchange="updateBadge({{ $item->id }}, this.value)"
                                    class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-emerald-400 bg-white">
                                <option value="rendah" {{ $item->tingkat_kerusakan == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                <option value="sedang" {{ $item->tingkat_kerusakan == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="tinggi" {{ $item->tingkat_kerusakan == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            </select>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                                <i class="fa-solid fa-check-circle"></i> Verifikasi
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-14 text-gray-400">
                        <i class="fa-solid fa-circle-check text-4xl mb-3 block text-emerald-300"></i>
                        <p class="font-medium">Semua laporan sudah diverifikasi!</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($laporan) && $laporan->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $laporan->links() }}
    </div>
    @endif
</div>

{{-- Modal Photo --}}
<div id="photoModal" onclick="closePhoto()"
     class="fixed inset-0 bg-black/70 z-[200] hidden items-center justify-center p-6">
    <img id="photoModalImg" src="" class="max-w-2xl max-h-[80vh] rounded-xl shadow-2xl object-contain">
</div>

@endsection

@push('scripts')
<script>
function updateBadge(id, nilai) {
    const badge = document.getElementById('badge-' + id);
    if (!badge) return;
    const map = {
        rendah: `<span class="badge badge-rendah"><i class="fa-solid fa-circle-info"></i> Rendah</span>`,
        sedang: `<span class="badge badge-sedang"><i class="fa-solid fa-triangle-exclamation"></i> Sedang</span>`,
        tinggi: `<span class="badge badge-tinggi"><i class="fa-solid fa-circle-exclamation"></i> Tinggi</span>`,
    };
    badge.innerHTML = map[nilai] || map['rendah'];
}

function openPhoto(src) {
    document.getElementById('photoModalImg').src = src;
    document.getElementById('photoModal').classList.remove('hidden');
    document.getElementById('photoModal').classList.add('flex');
}
function closePhoto() {
    document.getElementById('photoModal').classList.add('hidden');
    document.getElementById('photoModal').classList.remove('flex');
}
</script>
@endpush