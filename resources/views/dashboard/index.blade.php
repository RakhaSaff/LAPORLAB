@extends('layouts.app')

@section('title', 'LAPORLAB Saintek | Pelaporan Fasilitas Cepat')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

    .hero-section * { font-family: 'Plus Jakarta Sans', sans-serif; }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%      { opacity: 0.5; transform: scale(1.4); }
    }
    .animate-pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up-1 { animation: fadeInUp 0.8s 0.1s  both; }
    .fade-up-2 { animation: fadeInUp 0.8s 0.2s  both; }
    .fade-up-3 { animation: fadeInUp 0.8s 0.35s both; }
    .fade-up-4 { animation: fadeInUp 0.8s 0.5s  both; }

    .btn-primary .arrow { display: inline-block; transition: transform 0.25s ease; }
    .btn-primary:hover .arrow { transform: translateX(8px); }

    @keyframes floatBlob1 {
        0%, 100% { transform: translateY(0px) scale(1); }
        50%       { transform: translateY(-20px) scale(1.05); }
    }
    @keyframes floatBlob2 {
        0%, 100% { transform: translateY(0px) scale(1); }
        50%       { transform: translateY(20px) scale(1.08); }
    }
    @keyframes floatBlob3 {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50%       { transform: translate(-50%, -53%) scale(1.04); }
    }
    .blob-1 { animation: floatBlob1 6s ease-in-out infinite; }
    .blob-2 { animation: floatBlob2 7s ease-in-out infinite; }
    .blob-3 { animation: floatBlob3 8s ease-in-out infinite; }
</style>

{{-- WRAPPER UTAMA --}}
<div class="relative w-full"
     style="background-image: url('{{ asset('images/saintek_uinsa.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;">

    {{-- Overlay gradasi --}}
    <div class="absolute inset-0"
         style="background: linear-gradient(135deg,
                    rgba(7,172,125,0.69)  0%,
                    rgba(7,114,78,1)      45%,
                    rgba(245,159,11,0.8)  100%);
                mix-blend-mode: multiply; z-index: 0;">
    </div>

    <!-- HERO SECTION -->
    <section class="hero-section relative w-full min-h-screen flex items-center justify-center overflow-hidden"
             style="padding-top: 5rem;">

        <div id="blob1" class="blob-1 absolute rounded-full pointer-events-none"
            style="top:2.5rem;left:-5rem;width:24rem;height:24rem;
                   background:rgba(110,231,183,0.18);filter:blur(80px);"></div>
        <div id="blob2" class="blob-2 absolute rounded-full pointer-events-none"
            style="bottom:2.5rem;right:-5rem;width:20rem;height:20rem;
                   background:rgba(251,191,36,0.18);filter:blur(80px);"></div>
        <div id="blob3" class="blob-3 absolute rounded-full pointer-events-none"
            style="top:50%;left:50%;transform:translate(-50%,-50%);
                   width:700px;height:400px;
                   background:rgba(255,255,255,0.04);filter:blur(80px);"></div>

        <div class="relative z-10 max-w-5xl mx-auto px-6 lg:px-8 text-center text-white"
             style="filter:drop-shadow(0 4px 16px rgba(0,0,0,0.3));">
            <div class="max-w-4xl mx-auto">

                <!-- Badge -->
                <div class="fade-up-1 inline-flex items-center gap-3 mb-10"
                     style="background:rgba(255,255,255,0.18);backdrop-filter:blur(12px);
                            padding:0.75rem 1.5rem;border-radius:9999px;
                            border:1px solid rgba(255,255,255,0.30);
                            box-shadow:0 4px 20px rgba(0,0,0,0.15);
                            font-size:0.875rem;font-weight:600;">
                    <span class="animate-pulse-dot"
                          style="width:12px;height:12px;background:#34d399;border-radius:50%;
                                 display:inline-block;flex-shrink:0;"></span>
                    FAKULTAS SAINS &amp; TEKNOLOGI — UIN SUNAN AMPEL SURABAYA
                </div>

                <!-- Judul -->
                <h1 class="fade-up-2"
                    style="font-size:clamp(2.8rem,7vw,4.5rem);font-weight:900;
                           line-height:1.1;letter-spacing:-0.02em;margin-bottom:1.5rem;">
                    Kerusakan Fasilitas?<br>
                    <span style="color:#ffa200;">Laporkan Sekarang</span>
                </h1>

                <!-- Deskripsi -->
                <p class="fade-up-3"
   style="font-size:1.15rem;max-width:42rem;margin:0 auto 3rem;line-height:1.75;opacity:0.95;">
    LAPORLAB Saintek adalah aplikasi pelaporan kerusakan fasilitas laboratorium yang memudahkan
    mahasiswa melaporkan masalah secara cepat melalui foto dan sistem monitoring,
    sehingga pihak fakultas dapat segera menindaklanjuti perbaikan secara efisien.
</p>
                <!-- CTA Buttons -->
                <div class="fade-up-4"
                     style="display:flex;flex-wrap:wrap;justify-content:center;gap:1.5rem;margin-bottom:4rem;">
                    <a href="{{ route('laporan.create') }}" class="btn-primary"
                       style="display:inline-flex;align-items:center;justify-content:center;
                              padding:1.2rem 3rem;background:white;color:#064e3b;
                              font-weight:800;font-size:1.1rem;border-radius:9999px;
                              box-shadow:0 8px 32px rgba(0,0,0,0.2);text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.background='#e4870d';this.style.color='white';this.style.transform='translateY(-3px)';"
                       onmouseout="this.style.background='white';this.style.color='#064e3b';this.style.transform='translateY(0)';">
                        LAPORKAN KERUSAKAN
                        <span class="arrow" style="margin-left:0.75rem;font-size:1.25rem;">→</span>
                    </a>
                    <a href="{{ route('panduan') }}"
                       style="display:inline-flex;align-items:center;justify-content:center;
                              padding:1.2rem 3rem;background:rgba(255,255,255,0.18);
                              backdrop-filter:blur(12px);border:2px solid white;color:white;
                              font-weight:800;font-size:1.1rem;border-radius:9999px;
                              text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.30)';"
                       onmouseout="this.style.background='rgba(255,255,255,0.18)';">
                        Lihat Cara Kerja
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('partials.footer')
</div>

<script>
    // Parallax blobs
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        const blob1 = document.getElementById('blob1');
        const blob2 = document.getElementById('blob2');
        const blob3 = document.getElementById('blob3');
        if (blob1) blob1.style.transform = `translateY(${scrollY * 0.25}px) scale(1)`;
        if (blob2) blob2.style.transform = `translateY(${scrollY * 0.15}px) scale(1)`;
        if (blob3) blob3.style.transform = `translate(-50%, calc(-50% + ${scrollY * 0.2}px)) scale(1)`;
    });
</script>

@endsection