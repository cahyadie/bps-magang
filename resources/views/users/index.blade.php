<x-app-layout>
    <div class="p-4 sm:p-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        Manajemen User
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Kelola data pengguna, edit profil, dan reset password.
                    </p>
                </div>
                
                {{-- Tombol Tambah (Opsional: Aktifkan jika sudah buat Route Create) --}}
                {{-- 
                <a href="{{ route('users.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#d97757] to-[#e88968] border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-[#cc6f50] hover:to-[#d97757] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d97757] transition shadow-lg shadow-orange-900/20">
                    <i class="fas fa-plus mr-2"></i> Tambah User
                </a> 
                --}}
            </div>

            {{-- Alert Success --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition 
                     class="mb-6 bg-green-900/50 border border-green-500/50 text-green-300 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-300 hover:text-green-100">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            {{-- Filter & Search Bar --}}
            <div class="mb-6 bg-[#1a1a1a] border border-[#3a3a3a] rounded-xl p-4 flex flex-col sm:flex-row gap-4">
                <form action="{{ route('users.index') }}" method="GET" class="w-full relative">                    
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama atau email user..." 
                           class="w-full pl-10 pr-4 py-2 bg-[#252525] border border-[#3a3a3a] rounded-lg text-gray-300 focus:ring-[#d97757] focus:border-[#d97757] placeholder-gray-600">
                </form>
            </div>

            {{-- Table Container --}}
            <div class="bg-[#1a1a1a] border border-[#3a3a3a] rounded-xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-400">
                        <thead class="bg-[#252525] text-xs uppercase font-semibold text-gray-300">
                            <tr>
                                <th class="px-6 py-4 tracking-wider">User</th>
                                <th class="px-6 py-4 tracking-wider hidden sm:table-cell">Role</th>
                                <th class="px-6 py-4 tracking-wider hidden md:table-cell">Status Email</th>
                                <th class="px-6 py-4 tracking-wider hidden lg:table-cell">Bergabung</th>
                                <th class="px-6 py-4 tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3a3a3a]">
                            @forelse ($users as $user)
                                <tr class="hover:bg-[#252525]/50 transition duration-150 ease-in-out">
                                    {{-- Kolom Nama & Email --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-[#3a3a3a] flex items-center justify-center text-[#d97757] font-bold text-lg border border-[#4a4a4a]">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-white">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Role --}}
                                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                        @if ($user->isAdmin())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900/30 text-red-400 border border-red-800">
                                                Admin
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900/30 text-blue-400 border border-blue-800">
                                                User
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Kolom Status Email --}}
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        @if($user->email_verified_at)
                                            <div class="flex items-center text-green-400 text-xs">
                                                <i class="fas fa-check-circle mr-1.5"></i> Terverifikasi
                                            </div>
                                        @else
                                            <div class="flex items-center text-green-400 text-xs">
                                                <i class="fas fa-check-circle mr-1.5"></i> Terverifikasi
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Kolom Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-xs hidden lg:table-cell">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Edit Button --}}
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="p-2  text-blue-400 rounded-lg  hover:text-white "
                                               title="Edit User">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            {{-- Delete Button --}}
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 text-red-400 rounded-lg hover:text-white"
                                                            title="Hapus User">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-users-slash text-4xl mb-3 text-gray-600"></i>
                                            <p>Tidak ada user ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (Custom Dark Style) --}}
                @if ($users->hasPages())
                    <div class="bg-[#1a1a1a] px-4 py-3 border-t border-[#3a3a3a] sm:px-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>