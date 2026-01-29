@php
    $baseNavLink = 'flex items-center gap-3 py-3 mx-2 rounded-lg text-sm font-medium transition-all duration-200';
    $activeLink  = 'bg-[#d97757]/20 text-[#e88968]';
    $inactiveLink = 'text-gray-300 hover:bg-white/5 hover:text-white';
@endphp

<aside
    class="flex-shrink-0 bg-[#1a1a1a] border-r border-[#3a3a3a] flex flex-col h-dvh fixed top-0 left-0 z-50 transition-all duration-500 ease-in-out w-64 transform -translate-x-full lg:translate-x-0 lg:w-auto"
    :class="{ 
        'translate-x-0': mobileOpen,   
        'lg:w-64': expanded,           
        'lg:w-20': !expanded           
    }">

    {{-- 
        BAGIAN 1: HEADER SIDEBAR
    --}}
    <div class="h-20 flex items-center border-b border-[#3a3a3a] flex-shrink-0 px-4 transition-all duration-300">
        <div class="flex items-center w-full relative">

            {{-- Logo & Brand Name --}}
            <div class="flex items-center gap-3 overflow-hidden transition-all duration-300"
                 :class="expanded ? 'flex-shrink-0' : 'lg:hidden'">
                
                <img src="{{ asset('images/Magnet.png') }}" alt="Logo MagNet"
                     class="h-10 w-auto flex-shrink-0 transition-opacity duration-500">

                <h1 class="claude-title text-xl text-white font-semibold whitespace-nowrap overflow-hidden transition-all duration-200"
                    :class="expanded ? 'opacity-100 max-w-xs' : 'lg:opacity-0 lg:max-w-0'">
                    MagNet
                </h1>
            </div>

            {{-- Tombol Close (Mobile Only) --}}
            <button @click="mobileOpen = false"
                    class="text-gray-300 p-2 rounded-lg hover:bg-white/5 hover:text-white transition-colors lg:hidden ml-auto">
                <i class="fas fa-times w-5 text-center"></i>
            </button>

            {{-- Tombol Hamburger (Desktop Only) --}}
            <button @click="expanded = !expanded; localStorage.setItem('_x_expanded', expanded)"
                    class="text-gray-300 p-2 rounded-lg hover:bg-white/5 hover:text-white transition-colors hidden lg:block"
                    :class="expanded ? 'absolute right-0 top-1/2 transform -translate-y-1/2' : 'mx-auto w-full flex justify-center'">
                <i class="fas fa-bars w-5 text-center"></i>
            </button>
        </div>
    </div>

    {{-- 
        BAGIAN 2: NAVIGASI MENU
    --}}
    <nav class="flex-1 overflow-y-auto py-6 space-y-2">

        {{-- Menu: Dashboard --}}
        <a href="{{ route('dashboard') }}"
           @class([$baseNavLink, $activeLink => request()->routeIs('dashboard'), $inactiveLink => !request()->routeIs('dashboard')])
           :class="expanded ? 'px-4' : 'px-3 justify-center'" 
           x-tooltip.right="!expanded ? 'Dashboard' : ''">
            
            <i class="fas fa-home w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" 
                  x-transition:enter="transition ease-out duration-100 opacity-0" 
                  x-transition:enter-end="opacity-100">
                Dashboard
            </span>
        </a>

        {{-- Menu: Pendaftaran Magang --}}
        <a href="{{ route('daftar.index') }}"
           @class([$baseNavLink, $activeLink => request()->routeIs('daftar.*'), $inactiveLink => !request()->routeIs('daftar.*')])
           :class="expanded ? 'px-4' : 'px-3 justify-center'" 
           x-tooltip.right="!expanded ? 'Pendaftaran Magang' : ''">
            
            <i class="fas fa-user-plus w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" 
                  x-transition:enter="transition ease-out duration-100 opacity-0" 
                  x-transition:enter-end="opacity-100">
                Pendaftaran Magang
            </span>
        </a>

        {{-- Menu: Data Magang --}}
        <a href="{{ route('magang.index') }}"
           @class([$baseNavLink, $activeLink => request()->routeIs(['magang.index', 'magang.show', 'magang.edit']), $inactiveLink => !request()->routeIs(['magang.index', 'magang.show', 'magang.edit'])])
           :class="expanded ? 'px-4' : 'px-3 justify-center'" 
           x-tooltip.right="!expanded ? 'Data Magang' : ''">
            
            <i class="fas fa-th w-5 text-center flex-shrink-0"></i>
            
            <span class="whitespace-nowrap" x-show="expanded" 
                  x-transition:enter="transition ease-out duration-100 opacity-0" 
                  x-transition:enter-end="opacity-100">
                Data Magang
            </span>
        </a>

        {{-- ========================================================== --}}
        {{-- Menu: Manajemen User (HANYA UNTUK ADMIN) --}}
        {{-- ========================================================== --}}
        @if (auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}"
               @class([$baseNavLink, $activeLink => request()->routeIs('users.*'), $inactiveLink => !request()->routeIs('users.*')])
               :class="expanded ? 'px-4' : 'px-3 justify-center'" 
               x-tooltip.right="!expanded ? 'Manajemen User' : ''">
                
                <i class="fas fa-users-cog w-5 text-center flex-shrink-0"></i>
                
                <span class="whitespace-nowrap" x-show="expanded" 
                      x-transition:enter="transition ease-out duration-100 opacity-0" 
                      x-transition:enter-end="opacity-100">
                    Manajemen User
                </span>
            </a>
        @endif

        {{-- Menu: Profil Saya (Khusus Admin) --}}
        @if (auth()->user()->isAdmin())
            <a href="{{ route('profile.edit') }}"
               @class([$baseNavLink, $activeLink => request()->routeIs('profile.edit'), $inactiveLink => !request()->routeIs('profile.edit')])
               :class="expanded ? 'px-4' : 'px-3 justify-center'" 
               x-tooltip.right="!expanded ? 'Profil Saya' : ''">
                
                <i class="fas fa-user-circle w-5 text-center flex-shrink-0"></i>
                
                <span class="whitespace-nowrap" x-show="expanded" 
                      x-transition:enter="transition ease-out duration-100 opacity-0" 
                      x-transition:enter-end="opacity-100">
                    Profil Saya
                </span>
            </a>
        @endif
    </nav>

    {{-- 
        BAGIAN 3: USER FOOTER
    --}}
    <div class="p-5 border-t border-[#3a3a3a] flex-shrink-0">
        
        {{-- Info User --}}
        <div class="flex items-center" :class="expanded ? 'gap-3' : 'justify-center'">
            
            <div class="w-10 h-10 rounded-full bg-[#d97757]/20 text-[#e88968] flex items-center justify-center font-bold flex-shrink-0">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>

            <div class="overflow-hidden whitespace-nowrap" x-show="expanded"
                 x-transition:enter="transition ease-out duration-100 opacity-0" 
                 x-transition:enter-end="opacity-100">
                
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>

                @if (auth()->user()->isAdmin())
                    <span class="bg-red-600/20 text-red-400 px-2 py-0.5 rounded-xl text-[0.7rem] font-semibold inline-flex items-center mt-1">
                        <i class="fas fa-crown text-[0.625rem] mr-1"></i> ADMIN
                    </span>
                @else
                    <span class="bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-xl text-[0.7rem] font-semibold inline-flex items-center mt-1">
                        <i class="fas fa-user text-[0.625rem] mr-1"></i> USER
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
                
                <span class="whitespace-nowrap" x-show="expanded"
                      x-transition:enter="transition ease-out duration-100 opacity-0" 
                      x-transition:enter-end="opacity-100">
                    Logout
                </span>
            </button>
        </form>
    </div>
</aside>

<style>
    [x-tooltip] { position: relative; }
    @media (min-width: 1024px) {
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
            z-index: 100;
        }
    }
</style>