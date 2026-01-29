<x-app-layout>
    <div class="p-4 sm:p-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header & Back Button --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white">Edit User</h2>
                    <p class="mt-1 text-sm text-gray-400">Perbarui informasi profil dan keamanan akun.</p>
                </div>
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#2a2a2a] border border-[#3a3a3a] rounded-lg font-medium text-xs text-gray-300 uppercase tracking-widest hover:bg-[#3a3a3a] hover:text-white focus:outline-none transition shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Kiri: Form Edit (2 Kolom Lebar) --}}
                <div class="lg:col-span-2">
                    <div class="bg-[#1a1a1a] border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden">
                        
                        {{-- Card Header with Avatar --}}
                        <div class="p-6 bg-[#252525] border-b border-[#3a3a3a] flex items-center gap-4">
                            <div class="h-16 w-16 rounded-full bg-[#3a3a3a] flex items-center justify-center text-[#d97757] font-bold text-2xl border border-[#4a4a4a] shadow-inner">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-white">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="p-6">
                            <form method="post" action="{{ route('users.update', $user) }}" class="space-y-6">
                                @csrf
                                @method('put')

                                {{-- Nama --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                        <i class="fas fa-user mr-1 text-[#d97757]"></i> Nama Lengkap
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           class="w-full bg-[#252525] border border-[#3a3a3a] rounded-lg text-gray-200 focus:ring-[#d97757] focus:border-[#d97757] placeholder-gray-600"
                                           value="{{ old('name', $user->name) }}" required />
                                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                        <i class="fas fa-envelope mr-1 text-[#d97757]"></i> Alamat Email
                                    </label>
                                    <input type="email" name="email" id="email" 
                                           class="w-full bg-[#252525] border border-[#3a3a3a] rounded-lg text-gray-200 focus:ring-[#d97757] focus:border-[#d97757] placeholder-gray-600"
                                           value="{{ old('email', $user->email) }}" required />
                                    @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="relative py-2">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-[#3a3a3a]"></div>
                                    </div>
                                    <div class="relative flex justify-start">
                                        <span class="pr-3 bg-[#1a1a1a] text-sm font-medium text-gray-500">
                                            Ubah Password (Opsional)
                                        </span>
                                    </div>
                                </div>

                                {{-- Password Baru --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                            Password Baru
                                        </label>
                                        <input type="password" name="password" id="password" 
                                               class="w-full bg-[#252525] border border-[#3a3a3a] rounded-lg text-gray-200 focus:ring-[#d97757] focus:border-[#d97757] placeholder-gray-600"
                                               autocomplete="new-password" placeholder="Kosongkan jika tidak ubah" />
                                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                            Konfirmasi Password
                                        </label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="w-full bg-[#252525] border border-[#3a3a3a] rounded-lg text-gray-200 focus:ring-[#d97757] focus:border-[#d97757] placeholder-gray-600"
                                               autocomplete="new-password" />
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center justify-end pt-4 gap-3">
                                    <button type="reset" class="px-4 py-2 bg-transparent border border-[#3a3a3a] text-gray-400 rounded-lg hover:text-white transition">
                                        Reset
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#d97757] to-[#e88968] text-white rounded-lg font-semibold shadow-lg shadow-orange-900/20 hover:from-[#cc6f50] hover:to-[#d97757] transition transform hover:scale-105">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Sidebar Info & Danger Zone (1 Kolom Lebar) --}}
                <div class="space-y-8">
                    
                    {{-- Metadata Card --}}
                    <div class="bg-[#1a1a1a] border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden">
                        <div class="p-4 bg-[#252525] border-b border-[#3a3a3a]">
                            <h3 class="text-md font-semibold text-white">Metadata Akun</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-[#3a3a3a] last:border-0">
                                <span class="text-sm text-gray-400">User ID</span>
                                <span class="text-sm font-mono text-white bg-[#3a3a3a] px-2 py-1 rounded">#{{ $user->id }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#3a3a3a] last:border-0">
                                <span class="text-sm text-gray-400">Role</span>
                                @if ($user->isAdmin())
                                    <span class="text-xs bg-red-900/30 text-red-400 px-2 py-1 rounded border border-red-800">Admin</span>
                                @else
                                    <span class="text-xs bg-blue-900/30 text-blue-400 px-2 py-1 rounded border border-blue-800">User</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-[#3a3a3a] last:border-0">
                                <span class="text-sm text-gray-400">Bergabung</span>
                                <span class="text-sm text-white">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-400">Status Email</span>
                                <span class="text-xs {{ $user->email_verified_at ? 'text-green-400' : 'text-yellow-500' }}">
                                    <i class="fas {{ $user->email_verified_at ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                    {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    @if(auth()->id() !== $user->id)
                    <div class="bg-red-900/10 border border-red-900/30 rounded-xl overflow-hidden">
                        <div class="p-4 bg-red-900/20 border-b border-red-900/30 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <h3 class="text-md font-semibold text-red-400">Danger Zone</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-400 mb-4">
                                Menghapus user ini akan menghilangkan semua data terkait (pendaftaran magang, dll) secara permanen.
                            </p>
                            
                            <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                  onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus user ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition shadow-lg shadow-red-900/20">
                                    <i class="fas fa-trash-alt"></i> Hapus User Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>