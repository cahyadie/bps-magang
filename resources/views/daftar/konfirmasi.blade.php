<x-app-layout>
    <div class="claude-container flex items-center justify-center min-h-[80vh]">
        
        <div class="glass-panel w-full max-w-md p-8 text-center animate-fade-in-up">
            
            {{-- Ikon Status --}}
            @if(isset($isError) && $isError)
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-900/30 border-2 border-red-500/50 mb-6 shadow-[0_0_20px_rgba(239,68,68,0.3)]">
                    <i class="fas fa-times text-4xl text-red-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Konfirmasi Gagal</h2>
            @else
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-900/30 border-2 border-green-500/50 mb-6 shadow-[0_0_20px_rgba(34,197,94,0.3)]">
                    <i class="fas fa-check text-4xl text-green-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Konfirmasi Berhasil!</h2>
            @endif

            {{-- Pesan --}}
            <p class="text-gray-300 mb-8 leading-relaxed">
                {{ $message ?? 'Kehadiran Anda telah tercatat di sistem.' }}
            </p>

            {{-- Detail Singkat --}}
            <div class="bg-[#1a1a1a]/60 rounded-xl p-4 mb-8 border border-[#3a3a3a] text-left text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama:</span>
                    <span class="text-white font-medium">{{ $pendaftaran->nama_pendaftar }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status:</span>
                    <span class="text-green-400 font-medium uppercase tracking-wider text-xs bg-green-900/30 px-2 py-0.5 rounded">
                        {{ $pendaftaran->status }}
                    </span>
                </div>
                @if($pendaftaran->konfirmasi_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">Waktu Konfirmasi:</span>
                    <span class="text-white font-medium">
                        {{ \Carbon\Carbon::parse($pendaftaran->konfirmasi_at)->format('d M Y, H:i') }} WIB
                    </span>
                </div>
                @endif
            </div>

            {{-- Tombol Kembali --}}
            <a href="{{ route('dashboard') }}" class="claude-button w-full justify-center group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Dashboard
            </a>

        </div>
    </div>
</x-app-layout>