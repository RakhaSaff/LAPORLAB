# LAPORLAB Saintek

Aplikasi web pelaporan kerusakan barang di laboratorium **Fakultas Sains dan Teknologi UIN Sunan Ampel Surabaya**. Mahasiswa dan dosen melaporkan kerusakan lewat foto, admin memverifikasi dan mengelola perbaikan, dan laporan yang paling mendesak otomatis diurutkan paling atas.

> Sebelumnya bernama SMARTFAS Saintek.

## Latar Belakang

Pelaporan kerusakan fasilitas laboratorium selama ini belum terpusat, sehingga sulit dipantau dan sulit menentukan mana yang harus ditangani lebih dulu. LAPORLAB menyediakan satu tempat untuk melapor, memverifikasi, memperbaiki, dan merekap laporan.

## Fitur

**Pelapor (mahasiswa dan dosen)**
- Registrasi dan login
- Form laporan kerusakan: kode barang, nama barang, pilihan laboratorium, kegiatan akademik (ya/tidak), tingkat kerusakan, deskripsi, dan foto kerusakan (unggah file atau ambil lewat kamera)
- Memantau status laporan di halaman **Laporan Saya**

**Admin**
- Dashboard ringkasan laporan dan grafik per bulan
- Laporan Masuk dan Perlu Diverifikasi
- Manajemen Perbaikan (data teknisi, tanggal, bukti foto, status)
- Rekapan laporan
- Import Laporan dan Master Data aset
- Pembaruan status laporan secara real-time

**Perangkingan urgensi**
- Sistem berbasis aturan (*Rule-Based System*) dengan metode pembobotan (*Scoring System*)
- Skor dihitung dari bobot lokasi laboratorium, tingkat kerusakan, dan ada tidaknya kegiatan akademik
- Hasil skor dikelompokkan menjadi prioritas Tinggi, Sedang, atau Rendah, lalu dipakai untuk mengurutkan antrean perbaikan

## Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | PHP, Laravel |
| Frontend | Blade, Tailwind CSS, JavaScript |
| Database | PostgreSQL (dapat disesuaikan lewat `.env`) |
| Lingkungan lokal | Laragon |

## Cara Menjalankan

Prasyarat: PHP, Composer, Node.js, dan database yang sudah berjalan (misalnya lewat Laragon).

```bash
# 1. Clone repo
git clone https://github.com/RakhaSaff/LAPORLAB.git
cd LAPORLAB

# 2. Pasang dependensi
composer install
npm install

# 3. Siapkan konfigurasi
cp .env.example .env
php artisan key:generate
```

Buka file `.env`, lalu isi pengaturan database (`DB_*`). Setelah itu:

```bash
# 4. Buat tabel
php artisan migrate

# 5. Tautkan folder penyimpanan foto
php artisan storage:link

# 6. Jalankan
npm run dev
php artisan serve
```

Aplikasi berjalan di `http://127.0.0.1:8000`. Halaman admin ada di `/admin/login`.

## Struktur Branch

| Branch | Fungsi |
|---|---|
| `main` | Versi stabil |
| `dev` | Penggabungan semua fitur yang sedang dikerjakan |
| `fitur-*` | Satu branch untuk satu fitur (auth, laporan saya, dashboard admin, verifikasi, manajemen perbaikan, scoring prioritas, rekapan, import, master data, dan lainnya) |
| `hotfix` | Perbaikan cepat |

## Tangkapan Layar

_Tambahkan screenshot form laporan, dashboard admin, dan manajemen perbaikan di sini._

## Pengembang

Dikembangkan sebagai bagian dari tugas pengembangan sistem di Fakultas Sains dan Teknologi, UIN Sunan Ampel Surabaya.
