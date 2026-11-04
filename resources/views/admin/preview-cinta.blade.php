<form action="/admin/import-store" method="POST">
@csrf

<table border="1">
@foreach($data as $i => $d)
<tr>
<td>{{ $d['kode_barang'] }}</td>
<td>{{ $d['nama_barang'] }}</td>
</tr>

<input type="hidden" name="data[{{ $i }}][kode_barang]" value="{{ $d['kode_barang'] }}">
<input type="hidden" name="data[{{ $i }}][nama_barang]" value="{{ $d['nama_barang'] }}">
<input type="hidden" name="data[{{ $i }}][jenis_aset]" value="{{ $d['jenis_aset'] }}">
<input type="hidden" name="data[{{ $i }}][kategori]" value="{{ $d['kategori'] }}">
<input type="hidden" name="data[{{ $i }}][lokasi]" value="{{ $d['lokasi'] }}">
<input type="hidden" name="data[{{ $i }}][tahun]" value="{{ $d['tahun'] }}">
<input type="hidden" name="data[{{ $i }}][spesifikasi]" value="{{ $d['spesifikasi'] }}">

@endforeach
</table>

<button type="submit">Simpan</button>

</form>