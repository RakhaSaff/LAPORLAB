<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin — LAPORLAB Saintek</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 40%, #f0fdfa 70%, #fffbeb 100%);
            min-height: 100vh;
        }

        /* Floating blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }
        .blob-1 { width: 400px; height: 400px; background: #6ee7b7; top: -100px; left: -100px; animation-delay: 0s; }
        .blob-2 { width: 300px; height: 300px; background: #a7f3d0; bottom: -80px; right: -80px; animation-delay: 2s; }
        .blob-3 { width: 250px; height: 250px; background: #fde68a; top: 40%; right: 10%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        /* Card animation */
        .card-enter {
            animation: cardEnter 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Stagger form fields */
        .field-item {
            animation: fieldSlide 0.5s ease forwards;
            opacity: 0;
        }
        .field-item:nth-child(1) { animation-delay: 0.15s; }
        .field-item:nth-child(2) { animation-delay: 0.2s; }
        .field-item:nth-child(3) { animation-delay: 0.25s; }
        .field-item:nth-child(4) { animation-delay: 0.3s; }
        .field-item:nth-child(5) { animation-delay: 0.35s; }
        .field-item:nth-child(6) { animation-delay: 0.4s; }
        .field-item:nth-child(7) { animation-delay: 0.45s; }
        .field-item:nth-child(8) { animation-delay: 0.5s; }

        @keyframes fieldSlide {
            from { opacity: 0; transform: translateX(-12px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Input focus */
        .form-input {
            width: 100%;
            padding: 10px 16px;
            background: white;
            border: 1.5px solid #d1fae5;
            border-radius: 10px;
            font-size: 14px;
            color: #1f2937;
            transition: all 0.2s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 3px #d1fae5;
        }
        .form-input::placeholder { color: #9ca3af; }

        select.form-input { cursor: pointer; }

        /* Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            font-weight: 700;
            padding: 12px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px #05966940;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 0; height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px #05966955; }
        .btn-submit:active::after { width: 300px; height: 300px; }

        /* Logo pulse */
        .logo-icon {
            animation: logoPulse 3s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 0 0 0 #05966940; }
            50% { box-shadow: 0 0 0 10px #05966910; }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 min-h-screen">

    <!-- Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="relative z-10 w-full max-w-lg py-6">

        <!-- Card -->
        <div class="card-enter bg-white/80 backdrop-blur-sm border border-emerald-100 rounded-2xl shadow-xl shadow-emerald-100/50 p-8">

            <!-- Logo -->
            <div class="text-center mb-7">
                <div class="logo-icon inline-flex items-center justify-center w-14 h-14 bg-emerald-600 text-white rounded-xl text-2xl font-bold mb-3 shadow-lg">
                    S
                </div>
                <h1 class="text-xl font-bold text-gray-900">Pendaftaran Admin LAPORLAB</h1>
                <p class="text-gray-500 text-sm mt-1">Hanya untuk staf Fakultas Sains & Teknologi</p>
            </div>

            <!-- Error -->
            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3 flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.register') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="field-item">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="form-input" placeholder="admin.saintek">
                    </div>

                    <div class="field-item">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip') }}" required
                               class="form-input" placeholder="197001012000011001">
                    </div>

                    <div class="field-item">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="form-input" placeholder="Dr. Ahmad Fauzi">
                    </div>

                    <div class="field-item">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required class="form-input">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="field-item md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" required
                               class="form-input" placeholder="contoh: Kepala Laboratorium Komputer">
                    </div>

                    <div class="field-item md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="form-input" placeholder="admin@uinsa.ac.id">
                    </div>

                    <div class="field-item">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Password</label>
                        <input type="password" name="password" required
                               class="form-input" placeholder="••••••••">
                    </div>

                    <div class="field-item">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                               class="form-input" placeholder="••••••••">
                    </div>

                </div>

                <button type="submit" class="btn-submit mt-6">
                    Daftar Akun Admin
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                Sudah punya akun?
                <a href="{{ route('admin.login') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold transition">Masuk</a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            LAPORLAB Saintek · FST UIN Sunan Ampel Surabaya
        </p>
    </div>

</body>
</html>