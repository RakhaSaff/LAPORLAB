@extends('layouts.app')

@section('title', 'Daftar Akun - LAPORLAB Saintek')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    body, input, select, textarea, button {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .auth-wrapper {
        min-height: 100vh;
        padding-top: 5rem; /* space untuk navbar fixed */
        padding-bottom: 3rem;
        background-color: #fff7ed; /* orange-50 lembut */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-card {
        background: #ffffff;
        border-radius: 1.25rem;
        box-shadow: 0 8px 40px rgba(249, 115, 22, 0.10), 0 2px 8px rgba(0,0,0,0.06);
        padding: 2.5rem 2rem;
        width: 100%;
        max-width: 500px;
        margin: 0 1rem;
    }

    .auth-logo {
        width: 48px;
        height: 48px;
        background: #f97316;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 12px rgba(249,115,22,0.3);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 0.625rem;
        font-size: 0.95rem;
        color: #111827;
        background-color: #fafafa;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #f97316;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #9ca3af;
    }

    .divider {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .btn-daftar {
        width: 100%;
        padding: 0.9rem;
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
        font-weight: 700;
        font-size: 1rem;
        border-radius: 0.625rem;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
        box-shadow: 0 4px 16px rgba(249,115,22,0.35);
        margin-top: 0.5rem;
    }

    .btn-daftar:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .fade-in-1 { animation-delay: 0.05s; }
    .fade-in-2 { animation-delay: 0.12s; }
    .fade-in-3 { animation-delay: 0.20s; }
    .fade-in-4 { animation-delay: 0.28s; }
</style>

<div class="auth-wrapper">
    <div class="auth-card fade-in">

        <!-- Header -->
        <div class="text-center mb-7">
            <div class="auth-logo"></div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Daftar Akun LAPORLAB</h1>
            <p class="text-gray-500 text-sm">
                Isi data dengan benar untuk mengakses layanan pelaporan fasilitas.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nama Lengkap -->
            <div class="form-group fade-in fade-in-1">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" name="name" type="text" required autofocus
                        class="form-input" placeholder="Contoh: Rakha Safa Pratama"
                        value="{{ old('name') }}" />
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Tanggal Lahir & NIM/NIP (2 kolom) -->
            <div class="divider fade-in fade-in-1">
                <div class="form-group">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input id="tanggal_lahir" name="tanggal_lahir" type="date" required
                            class="form-input" />
                    @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="form-select">
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- NIM / NIP -->
            <div class="form-group fade-in fade-in-2">
                <label for="nim_nip" class="form-label">NIM</label>
                <input id="nim_nip" name="nim_nip" type="text" required
                        class="form-input" placeholder="Masukkan: NIM Mahasiswa kamu"
                        value="{{ old('nim_nip') }}" />
                @error('nim_nip') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Program Studi -->
            <div class="form-group fade-in fade-in-2">
                <label for="prodi" class="form-label">Program Studi</label>
                <select id="prodi" name="prodi" required class="form-select">
                    <option value="">Pilih Program Studi</option>
                    @foreach(['Sistem Informasi','Arsitektur','Teknik Lingkungan','Teknik Sipil','Biologi','Matematika','Ilmu Kelautan'] as $p)
                        <option value="{{ $p }}" {{ old('prodi')==$p?'selected':'' }}>{{ $p }}</option>
                    @endforeach
                </select>
                @error('prodi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Alamat -->
            <div class="form-group fade-in fade-in-2">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" required
                           class="form-textarea" placeholder="Alamat lengkap anda...">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- No HP & Email (2 kolom) -->
            <div class="divider fade-in fade-in-3">
                <div class="form-group">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input id="no_hp" name="no_hp" type="tel" required
                            class="form-input" placeholder="0812xxxxxxx"
                            value="{{ old('no_hp') }}" />
                    @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" required
                            class="form-input" placeholder="contoh@email.com"
                            value="{{ old('email') }}" />
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Password & Konfirmasi (2 kolom) -->
            <div class="divider fade-in fade-in-3">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" type="password" required
                            class="form-input" placeholder="••••••••"
                            autocomplete="new-password" />
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="form-input" placeholder="••••••••" />
                </div>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit" class="btn-daftar fade-in fade-in-4">
                Daftar Akun →
            </button>

            <div class="text-center mt-5 text-sm text-gray-500 fade-in fade-in-4">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:#f97316; font-weight:600;" class="hover:underline">Masuk di sini</a>
            </div>
        </form>

    </div>
</div>

@endsection