{{-- resources/views/layouts/sidebar.blade.php --}}

<aside
    :class="expanded ? 'w-64' : 'w-20'"
    class="flex-shrink-0 bg-[#1a1a1a] border-r border-[#3a3a3a] flex flex-col h-screen fixed top-0 left-0 z-40 transition-all duration-500 ease-in-out">

    {{-- HEADER SIDEBAR DENGAN TOMBOL BURGER --}}
    <div class="h-16 flex items-center border-b border-[#3a3a3a] flex-shrink-0 px-4">
        <div class="flex items-center w-full" :class="expanded ? 'justify-between' : 'justify-center'">
            
            {{-- Judul (Hanya tampil saat 'expanded') --}}
            <h1 class="claude-title text-xl text-white font-semibold whitespace-nowrap overflow-hidden transition-all duration-200"
                :class="expanded ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0'">
                BPS Bantul
            </h1>

            {{-- Tombol Toggle --}}
            <button @click="expanded = !expanded"
                class="text-gray-300 p-2 rounded-lg hover:bg-white/5 hover:text-white transition-colors">
                <i class="fas fa-bars w-5 text-center"></i>
            </button>
        </div>
    </div>

    {{-- NAVIGASI --}}
    <nav class="flex-1 overflow-y-auto py-4 space-y-2">

        {{-- 
          Pola untuk setiap link:
          1. :class="expanded ? 'px-4' : 'px-3 justify-center'" - Mengubah padding & centering
          2. <i ... class="flex-shrink-0"> - Mencegah icon menyusut
          3. <span ... x-show="expanded" ...> - Menyembunyikan teks
        --}}

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 py-2.5 mx-2 rounded-lg text-sm font-medium transition-all duration-200
            {{ request()->routeIs('dashboard') ? 'bg-[#d97757]/20 text-[#e88968]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}"
            :class="expanded ? 'px-4' : 'px-3 justify-center'"
            x-tooltip.right="!expanded ? 'Dashboard' : ''">
            
            <i class="fas fa-home w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                Dashboard
            </span>
        </a>

        <a href="{{ route('daftar.index') }}"
            class="flex items-center gap-3 py-2.5 mx-2 rounded-lg text-sm font-medium transition-all duration-200
            {{ request()->routeIs('daftar.*') ? 'bg-[#d97757]/20 text-[#e88968]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}"
            :class="expanded ? 'px-4' : 'px-3 justify-center'"
            x-tooltip.right="!expanded ? 'Pendaftaran Magang' : ''">
            
            <i class="fas fa-user-plus w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                Pendaftaran Magang
            </span>
        </a>

        <a href="{{ route('magang.index') }}"
            class="flex items-center gap-3 py-2.5 mx-2 rounded-lg text-sm font-medium transition-all duration-200
            {{ (request()->routeIs('magang.index') || request()->routeIs('magang.show') || request()->routeIs('magang.edit')) ? 'bg-[#d97757]/20 text-[#e88968]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}"
            :class="expanded ? 'px-4' : 'px-3 justify-center'"
            x-tooltip.right="!expanded ? 'Data Magang' : ''">
            
            <i class="fas fa-th w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                Data Magang
            </span>
        </a>

        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 py-2.5 mx-2 rounded-lg text-sm font-medium transition-all duration-200
            {{ request()->routeIs('profile.edit') ? 'bg-[#d97757]/20 text-[#e88968]' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}"
            :class="expanded ? 'px-4' : 'px-3 justify-center'"
            x-tooltip.right="!expanded ? 'Profil Saya' : ''">
            
            <i class="fas fa-user-circle w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                Profil Saya
            </span>
        </a>
    </nav>

    {{-- AREA USER & LOGOUT --}}
    <div class="p-4 border-t border-[#3a3a3a] flex-shrink-0">
        
        {{-- Profile Info --}}
        <div class="flex items-center" :class="expanded ? 'gap-3' : 'justify-center'">
            
            {{-- Avatar (Selalu tampil) --}}
            <div
                class="w-10 h-10 rounded-full bg-[#d97757]/20 text-[#e88968] flex items-center justify-center font-bold flex-shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>

            {{-- Nama & Role (Hanya tampil saat 'expanded') --}}
            <div class="overflow-hidden whitespace-nowrap" x-show="expanded"
                x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                
                @if (auth()->user()->isAdmin())
                    <span
                        style="background-color: rgba(220, 38, 38, 0.2); color: #f87171; padding: 0.125rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">
                        <i class="fas fa-crown" style="font-size: 0.625rem; margin-right: 0.25rem;"></i>ADMIN
                    </span>
                @else
                    <span
                        style="background-color: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.125rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">
                        <i class="fas fa-user" style="font-size: 0.625rem; margin-right: 0.25rem;"></i>USER
                    </span>
                @endif
            </div>
        </div>

        {{-- Tombol Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                class="w-full bg-gradient-to-r from-red-600/80 to-red-700/80 hover:from-red-600 hover:to-red-700 text-white py-2.5 rounded-lg font-medium transition-all duration-300 inline-flex items-center justify-center gap-2 text-sm shadow-lg shadow-red-600/20 hover:shadow-red-600/30"
                :class="expanded ? 'px-4' : 'px-2.5'">
                
                <i class="fas fa-sign-out-alt w-5 text-center flex-shrink-0"></i>
                
                <span class="whitespace-nowrap" x-show="expanded" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    Logout
                </span>
            </button>
        </form>
    </div>
</aside>

{{-- Opsional: Tambahkan ini untuk Tooltip saat sidebar terlipat --}}
<style>
    [x-tooltip] {
        position: relative;
    }
    [x-tooltip]:hover::after {
        content: attr(x-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        margin-left: 12px;
        padding: 4px 8px;
        background-color: #1a1a1a;
        color: white;
        border: 1px solid #3a3a3a;
        border-radius: 4px;
        font-size: 0.875rem;
        white-space: nowrap;
        z-index: 50;
    }
</style>

{{-- 
  Script Alpine.js ditempatkan di sini.
  Karena file ini di-@include di 'app.blade.php' dan 'main.blade.php',
  script ini akan otomatis termuat di kedua layout tersebut tanpa duplikasi.
--}}
<script src="//unpkg.com/alpinejs" defer></script>