{{-- resources/views/layouts/sidebar.blade.php --}}

<aside class="w-64 flex-shrink-0 bg-[#1a1a1a] border-r border-[#3a3a3a] flex flex-col h-screen fixed top-0 left-0 z-40">
    <div class="h-16 flex items-center justify-center px-4 border-b border-[#3a3a3a] flex-shrink-0">
        <h1 class="claude-title text-xl text-white font-semibold">
            BPS Bantul
        </h1>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 space-y-2">

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-sm font-medium transition-colors
            {{ request()->routeIs('dashboard')
                ? 'bg-[#d97757]/20 text-[#e88968]'
                : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <i class="fas fa-home w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('daftar.index') }}"
            class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-sm font-medium transition-colors
            {{ request()->routeIs('daftar.*')
                ? 'bg-[#d97757]/20 text-[#e88968]'
                : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <i class="fas fa-user-plus w-5 text-center"></i>
            <span>Pendaftaran Magang</span>
        </a>

        <a href="{{ route('magang.index') }}"
            class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-sm font-medium transition-colors
            {{ (request()->routeIs('magang.index') || request()->routeIs('magang.show') || request()->routeIs('magang.edit'))
                ? 'bg-[#d97757]/20 text-[#e88968]'
                : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <i class="fas fa-th w-5 text-center"></i>
            <span>Data Magang</span>
        </a>

        {{-- ✅ Tombol "Tambah Data" SUDAH DIHAPUS DARI SINI --}}

        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-lg text-sm font-medium transition-colors
            {{ request()->routeIs('profile.edit')
                ? 'bg-[#d97757]/20 text-[#e88968]'
                : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
            <i class="fas fa-user-circle w-5 text-center"></i>
            <span>Profil Saya</span>
        </a>
    </nav>

    <div class="p-4 border-t border-[#3a3a3a] flex-shrink-0">
        <div class="flex items-center gap-3">
            {{-- Placeholder avatar --}}
            <div class="w-10 h-10 rounded-full bg-[#d97757]/20 text-[#e88968] flex items-center justify-center font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
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

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                class="w-full bg-gradient-to-r from-red-600/80 to-red-700/80 hover:from-red-600 hover:to-red-700 text-white px-4 py-2.5 rounded-lg font-medium transition-all duration-300 inline-flex items-center justify-center gap-2 text-sm shadow-lg shadow-red-600/20 hover:shadow-red-600/30">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>