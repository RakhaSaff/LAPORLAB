@extends('layouts.app')

@section('title', 'Laporan Terkirim')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
    --green-dark: #0a4a2e;
    --green-mid: #0f6b3e;
    --green-bright: #16a34a;
    --green-light: #22c55e;
    --orange: #f59e0b;
    --gray-100: #f3f4f6;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-900: #111827;
}
body { font-family: 'DM Sans', sans-serif; background: #f0f5f2; }
.success-wrapper {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}
.success-card {
    background: white;
    border-radius: 20px;
    padding: 48px 40px;
    max-width: 480px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
    animation: slideUp .4s ease;
}
@keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
.success-icon {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, var(--green-light), var(--green-bright));
    border-radius: 50%;
    display: grid; place-items: center;
    margin: 0 auto 24px;
    font-size: 2.2rem;
    box-shadow: 0 8px 28px rgba(22,163,74,0.3);
}
.success-card h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.5rem; font-weight: 800;
    color: var(--gray-900); margin-bottom: 10px;
}
.success-card p { color: var(--gray-500); font-size: 0.9rem; line-height: 1.6; margin-bottom: 8px; }
.ticket-box {
    background: var(--gray-100);
    border-radius: 10px;
    padding: 14px 20px;
    margin: 20px 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    font-size: 1.1rem;
    color: var(--green-dark);
    letter-spacing: 0.1em;
}
.ticket-box small {
    display: block;
    font-size: 0.72rem;
    font-weight: 400;
    color: var(--gray-400);
    margin-bottom: 4px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 18px 0 24px;
    text-align: left;
}
.detail-item {
    background: #f9fafb;
    border-radius: 8px;
    padding: 10px 14px;
}
.detail-item .d-label {
    font-size: 0.7rem; font-weight: 700;
    color: var(--gray-400); text-transform: uppercase;
    letter-spacing: 0.05em; margin-bottom: 3px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.detail-item .d-val {
    font-size: 0.85rem; font-weight: 600;
    color: var(--gray-900);
}
.btn-home {
    display: block; width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
    color: white; text-decoration: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700; font-size: 0.9rem;
    border-radius: 10px;
    transition: all .2s;
    box-shadow: 0 4px 14px rgba(15,107,62,0.3);
    margin-bottom: 12px;
}
.btn-home:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(15,107,62,0.4); color: white; }
.btn-status {
    display: block; width: 100%;
    padding: 11px;
    background: transparent;
    border: 1.5px solid #d1d5db;
    color: var(--gray-500); text-decoration: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600; font-size: 0.875rem;
    border-radius: 10px;
    transition: all .2s;
}
.btn-status:hover { border-color: var(--green-bright); color: var(--green-bright); }
</style>
@endpush

@section('content')
<div class="success-wrapper">
    <div class="success-card">
        <div class="success-icon">✅</div>
        <h2>Laporan Terkirim!</h2>
        <p>Laporan kerusakan fasilitas Anda berhasil dikirim. Tim teknisi akan segera menindaklanjuti.</p>

        <div class="ticket-box">
            <small>Nomor Tiket</small>
            {{ $laporan->nomor_tiket }}
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="d-label">Nama Barang</div>
                <div class="d-val">{{ $laporan->nama_barang }}</div>
            </div>
            <div class="detail-item">
                <div class="d-label">Jenis</div>
                <div class="d-val">{{ ucfirst($laporan->jenis_barang) }}</div>
            </div>
            <div class="detail-item" style="grid-column: span 2;">
                <div class="d-label">Lokasi</div>
                <div class="d-val">{{ $laporan->tempat_label }}</div>
            </div>
            <div class="detail-item" style="grid-column: span 2;">
                <div class="d-label">Status</div>
                <div class="d-val" style="color: var(--orange);">⏳ Menunggu Diproses</div>
            </div>
        </div>

        <p style="font-size:0.78rem;color:var(--gray-400);margin-bottom:20px;">
            Simpan nomor tiket untuk memantau status laporan Anda.
        </p>

        <a href="{{ route('home') }}" class="btn-home">← Kembali ke Beranda</a>
        <a href="{{ route('laporan.index') }}" class="btn-status">Lihat Semua Laporan Saya</a>
    </div>
</div>
@endsection
