<nav x-data="{ open: false }" style="background:white; border-bottom:1px solid #e5e7eb; position:fixed; width:100%; z-index:50; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <div style="display:flex; align-items:center; height:64px; width:100%; padding-left:24px; padding-right:32px;">

        <!-- Logo -->
        <div style="width:32px; height:32px; background:#047857; border-radius:8px; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:1rem;">
    L
</div>
<div style="font-weight:700; font-size:1.1rem; white-space:nowrap;">
    <span style="color:#111827;">LAPORLAB</span>
    <span style="color:#047857;"> Saintek</span>
</div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex" style="align-items:center; gap:24px; font-size:0.875rem; font-weight:500; margin-left:auto; margin-right:48px;">
            <a href="{{ route('home') }}"
               style="color:{{ request()->routeIs('home') ? '#047857' : '#374151' }}; text-decoration:none; white-space:nowrap; font-weight:{{ request()->routeIs('home') ? '700' : '500' }};"
               onmouseover="this.style.color='#047857'" onmouseout="this.style.color='{{ request()->routeIs('home') ? '#047857' : '#374151' }}'">
               Beranda
            </a>
            <a href="{{ route('laporan.create') }}"
               style="color:{{ request()->routeIs('laporan.create') ? '#047857' : '#374151' }}; text-decoration:none; white-space:nowrap; font-weight:{{ request()->routeIs('laporan.create') ? '700' : '500' }};"
               onmouseover="this.style.color='#047857'" onmouseout="this.style.color='{{ request()->routeIs('laporan.create') ? '#047857' : '#374151' }}'">
               Laporkan Kerusakan
            </a>
            <a href="{{ route('laporan.saya') }}"
               style="color:{{ request()->routeIs('laporan.saya') ? '#047857' : '#374151' }}; text-decoration:none; white-space:nowrap; font-weight:{{ request()->routeIs('laporan.saya') ? '700' : '500' }};"
               onmouseover="this.style.color='#047857'" onmouseout="this.style.color='{{ request()->routeIs('laporan.saya') ? '#047857' : '#374151' }}'">
               Laporan Saya
            </a>
            <a href="{{ route('panduan') }}"
               style="color:{{ request()->routeIs('panduan') ? '#047857' : '#374151' }}; text-decoration:none; white-space:nowrap; font-weight:{{ request()->routeIs('panduan') ? '700' : '500' }};"
               onmouseover="this.style.color='#047857'" onmouseout="this.style.color='{{ request()->routeIs('panduan') ? '#047857' : '#374151' }}'">
               Panduan
            </a>
        </div>

        <!-- Auth Buttons -->
        <div class="hidden md:flex" style="align-items:center; gap:12px; flex-shrink:0;">
            @auth
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; background:#047857; border-radius:50%;
                                display:flex; align-items:center; justify-content:center;
                                color:white; font-weight:700; font-size:0.875rem; flex-shrink:0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span style="color:#374151; font-weight:600; font-size:0.875rem; white-space:nowrap;">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                style="padding:8px 16px; background:#f3f4f6; color:#374151; font-weight:600;
                                       font-size:0.875rem; border-radius:8px; border:none; cursor:pointer;
                                       white-space:nowrap; transition:all 0.2s;"
                                onmouseover="this.style.background='#fee2e2';this.style.color='#dc2626';"
                                onmouseout="this.style.background='#f3f4f6';this.style.color='#374151';">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                   style="color:#374151; font-weight:600; font-size:0.875rem; text-decoration:none; white-space:nowrap;"
                   onmouseover="this.style.color='#047857'" onmouseout="this.style.color='#374151'">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   style="padding:8px 20px; background:#047857; color:white; font-weight:600; font-size:0.875rem; border-radius:8px; text-decoration:none; white-space:nowrap; box-shadow:0 2px 6px rgba(0,0,0,0.15);"
                   onmouseover="this.style.background='#065f46'" onmouseout="this.style.background='#047857'">
                    Daftar
                </a>
            @endauth
        </div>

        <!-- Hamburger (mobile) -->
        <button @click="open = !open" class="md:hidden"
                style="margin-left:auto; background:none; border:none; font-size:1.5rem; color:#374151; cursor:pointer;">
            <i class="fa-solid" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
        </button>

    </div>

    <!-- Mobile Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="background:white; border-top:1px solid #e5e7eb; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <div style="padding:24px; display:flex; flex-direction:column; gap:20px; font-size:1rem; font-weight:500; color:#1f2937;">
            <a href="{{ route('home') }}" style="color:#1f2937; text-decoration:none;">Beranda</a>
            <a href="{{ route('laporan.create') }}" style="color:#1f2937; text-decoration:none;">Laporkan Kerusakan</a>
            <a href="{{ route('laporan.saya') }}" style="color:#1f2937; text-decoration:none;">Laporan Saya</a>
            <a href="{{ route('panduan') }}" style="color:#1f2937; text-decoration:none;">Panduan</a>

            <div style="padding-top:16px; border-top:1px solid #e5e7eb; display:flex; flex-direction:column; gap:12px;">
                @auth
                    <div style="display:flex; align-items:center; gap:10px; padding:8px 0;">
                        <div style="width:32px; height:32px; background:#047857; border-radius:50%;
                                    display:flex; align-items:center; justify-content:center;
                                    color:white; font-weight:700; font-size:0.875rem; flex-shrink:0;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span style="color:#374151; font-weight:600; font-size:0.875rem;">
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                style="display:block; width:100%; text-align:center; padding:12px;
                                       background:#fee2e2; color:#dc2626; font-weight:600;
                                       border-radius:8px; border:none; cursor:pointer; font-size:1rem;">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       style="display:block; text-align:center; padding:12px; color:#374151; font-weight:600; text-decoration:none;">
                       Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       style="display:block; text-align:center; padding:12px; background:#047857; color:white; font-weight:600; border-radius:8px; text-decoration:none;">
                       Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>