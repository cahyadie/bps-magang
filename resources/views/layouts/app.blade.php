<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

{{-- 
  1. Menambahkan x-data="{ expanded: true }" untuk mengontrol status sidebar.
  2. Menambahkan kelas dark mode untuk kompatibilitas (opsional tapi disarankan).
--}}
<body class="font-sans antialiased" x-data="{ expanded: true }">
    
    {{-- 
      2. Mengganti div.min-h-screen lama dengan struktur flex baru 
         untuk layout sidebar + konten utama.
    --}}
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        {{-- 3. Memanggil sidebar baru Anda --}}
        @include('layouts.sidebar')

        {{-- 4. Wrapper Konten Utama --}}
        {{-- 
          :class dinamis ini akan mengubah margin-left
          - ml-64 (lebar sidebar penuh) saat expanded = true
          - ml-20 (lebar sidebar terlipat) saat expanded = false
        --}}
        <div class="flex-1 flex flex-col transition-all duration-300 ease-in-out"
             :class="expanded ? 'ml-64' : 'ml-20'">

            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 p-6 sm:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- 5. Menambahkan script Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>

</html>