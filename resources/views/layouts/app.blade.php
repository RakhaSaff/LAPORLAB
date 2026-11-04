<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LAPORLAB Saintek | FST UINSA')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- CSS tambahan dari masing-masing halaman --}}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <x-navbar />

    <main>
        @yield('content')
    </main>

    {{-- JS tambahan dari masing-masing halaman --}}
    @stack('scripts')
</body>
</html>