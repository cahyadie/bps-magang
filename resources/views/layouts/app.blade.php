<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/Magnet.png') }}">

    <title>{{ config('app.name', 'MagNet') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        if (localStorage.getItem('_x_expanded') === 'false') {
            document.documentElement.classList.add('sidebar-collapsed-on-load');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body class="font-sans antialiased view-grid"
      x-data="{ expanded: JSON.parse(localStorage.getItem('_x_expanded') ?? 'true'), mobileOpen: false }"
      x-init="document.documentElement.classList.remove('sidebar-collapsed-on-load')"
      :class="{ 'overflow-hidden': mobileOpen }">

    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>
    <div class="particle" style="top: 20%; left: 30%; animation-delay: 0s;"></div>
    <div class="particle" style="top: 70%; left: 60%; animation-delay: 1.5s;"></div>
    <div class="particle" style="top: 40%; left: 80%; animation-delay: 2.5s;"></div>

    <header x-cloak
            class="lg:hidden fixed top-0 left-0 right-0 z-30 flex items-center justify-start gap-4 h-16 px-6 bg-[#1a1a1a]/80 backdrop-blur-sm border-b border-[#3a3a3a]">
        <button @click="mobileOpen = true" class="text-gray-300 p-2 -ml-2 rounded-lg hover:bg-white/5 hover:text-white">
            <i class="fas fa-bars w-5 text-center"></i>
        </button>
        <h1 class="claude-title text-xl text-white font-semibold">
            MagNet
        </h1>
    </header>

    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <main class="flex-1 transition-all duration-500 ease-in-out pt-16 lg:pt-0"
              :class="expanded ? 'lg:pl-64' : 'lg:pl-20'">
            
            {{-- Slot untuk konten halaman --}}
            {{ $slot }}
            
        </main>
    </div>

    <div x-cloak x-show="mobileOpen" @click="mobileOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden">
    </div>

    {{-- AlpineJS di-defer agar tidak blocking render --}}
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>