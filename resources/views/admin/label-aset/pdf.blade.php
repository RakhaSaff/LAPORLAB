<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Label Aset — LAPORLAB Saintek</title>
<style>
    * { font-family: Arial, sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
    body { background: #fff; padding: 20px; font-size: 12px; }
    .header { border-bottom: 2px solid #059669; padding-bottom: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
    .header-title { font-size: 18px; font-weight: bold; color: #059669; }
    .header-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .info-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin-bottom: 16px; display: flex; gap: 16px; }
    .info-item { flex: 1; }
    .info-label { font-size: 10px; color: #6b7280; margin-bottom: 2px; }
    .info-value { font-size: 12px; font-weight: bold; color: #111; }
    .label-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .label-card { border: 2px solid #d1d5db; border-radius: 8px; padding: 10px; text-align: center; page-break-inside: avoid; }
    .label-code { font-size: 11px; font-weight: bold; font-family: 'Courier New', monospace; color: #065f46; letter-spacing: 0.5px; word-break: break-all; }
    .label-num { font-size: 10px; color: #9ca3af; margin-top: 4px; }
    .qr-placeholder { width: 48px; height: 48px; background: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 4px; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #9ca3af; }
    .footer { margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 10px; font-size: 10px; color: #9ca3af; display: flex; justify-content: space-between; }
    .lock-notice { background: #fefce8; border: 1px solid #fde68a; border-radius: 6px; padding: 8px 12px; margin-bottom: 16px; font-size: 11px; color: #92400e; }
    .foto-img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="header-title">LAPORLAB Saintek</div>
        <div class="header-sub">Fakultas Sains dan Teknologi — UIN Sunan Ampel Surabaya</div>
        <div class="header-sub">Label Aset: {{ $labelAset->jenis_barang }} • {{ count($labels) }} unit</div>
    </div>
    @if($fotoBase64)
    <img src="{{ $fotoBase64 }}" alt="Foto Barang" class="foto-img">
    @endif
</div>

<div class="info-box">
    <div class="info-item">
        <div class="info-label">Jenis Barang</div>
        <div class="info-value">{{ $labelAset->jenis_barang }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">Kode</div>
        <div class="info-value" style="color:#059669">{{ $labelAset->kode_barang }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">Lokasi</div>
        <div class="info-value">{{ $labelAset->lokasi ?? '-' }}</div>
    </div>
    <div class="info-item">
        <div class="info-label">Jumlah Label</div>
        <div class="info-value">{{ $labelAset->jumlah }} unit</div>
    </div>
    <div class="info-item">
        <div class="info-label">Tanggal Generate</div>
        <div class="info-value">{{ $labelAset->created_at->format('d M Y, H:i') }}</div>
    </div>
</div>

<div class="lock-notice">
    🔒 Label di bawah ini sudah tersimpan permanen dan tidak dapat diubah.
    Range nomor urut: {{ str_pad($labelAset->nomor_urut_awal, 3, '0', STR_PAD_LEFT) }} — {{ str_pad($labelAset->nomor_urut_akhir, 3, '0', STR_PAD_LEFT) }}
</div>

<div class="label-grid">
    @foreach($labels as $i => $label)
    <div class="label-card">
        <div class="qr-placeholder">QR</div>
        <div class="label-code">{{ $label }}</div>
        <div class="label-num">No. {{ str_pad($i + 1, 3, '0', STR_PAD_LEFT) }}</div>
    </div>
    @endforeach
</div>

<div class="footer">
    <span>LAPORLAB Saintek — FST UINSA</span>
    <span>Dicetak: {{ now()->format('d M Y, H:i') }}</span>
</div>

</body>
</html>
