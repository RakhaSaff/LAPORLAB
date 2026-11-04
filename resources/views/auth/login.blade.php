@extends('layouts.app')

@section('title', 'Masuk - LAPORLAB Saintek')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    body, input, button {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .auth-container {
        max-width: 420px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 1rem;
        color: #111827;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    .btn-masuk {
        width: 100%;
        padding: 0.875rem;
        background-color: #f97316;
        color: white;
        font-weight: 600;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-masuk:hover {
        background-color: #ea580c;
    }

    .fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .fade-in-1 { animation-delay: 0.1s; }
    .fade-in-2 { animation-delay: 0.2s; }
    .fade-in-3 { animation-delay: 0.3s; }
</style>

<div class="min-h-screen bg-white flex items-center justify-center">
    <div class="auth-container fade-in">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke LAPORLAB Saintek</h1>
            <p class="text-gray-600 text-sm">
                Masukkan email dan password untuk melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-0">
            @csrf

            <!-- Email -->
            <div class="form-group fade-in fade-in-1">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" required autofocus 
                       class="form-input" placeholder="contoh@email.com" />
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div class="form-group fade-in fade-in-2">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" required 
                       class="form-input" autocomplete="current-password" />
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Tombol Masuk -->
            <button type="submit" class="btn-masuk fade-in fade-in-3">
                Masuk
            </button>

            <div class="text-center mt-6 text-sm text-gray-600 fade-in fade-in-3">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-orange-600 hover:underline font-medium">Daftar di sini</a>
            </div>

<!-- Lupa password? (fitur belum aktif) -->
            <div class="text-center mt-3 text-sm text-gray-500">
    Lupa password? Hubungi admin.
            </div>
        </form>
    </div>
</div>
@endsection