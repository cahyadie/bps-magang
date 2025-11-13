{{-- resources/views/daftar/index.blade.php --}}
<x-main-layout>
    <div class="claude-container">
        
        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="claude-title text-2xl text-white">
                        Pendaftaran Magang
                    </h2>
                    
                    <a href="{{ route('daftar.create') }}" 
                       class="claude-button px-5 py-2.5 inline-flex items-center gap-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar Sekarang</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-8 px-6">
            
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="px-6 mb-4">
                    <div class="success-alert">
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Container Tabel --}}
            <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-[#1a1a1a]/50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Pendaftar</th>
                                <th scope="col" class="px-6 py-3">Asal Kampus</th>
                                <th scope="col" class="px-6 py-3">Periode</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                {{-- ✅ KOLOM BARU DITAMBAHKAN --}}
                                <th scope="col" class="px-6 py-3">Konfirmasi Kehadiran</th>
                                @if(auth()->user()->isAdmin())
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftarans as $pendaftar)
                                <tr class="border-b border-[#3a3a3a] hover:bg-[#ffffff]/5">
                                    <td class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                        {{ $pendaftar->nama_pendaftar }}
                                    </td>
                                    <td class="px-6 py-4">{{ $pendaftar->asal_kampus }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($pendaftar->tanggal_mulai)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($pendaftar->tanggal_selesai)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($pendaftar->status == 'pending')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-900 text-yellow-300">Menunggu</span>
                                        @elseif ($pendaftar->status == 'approved')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-900 text-green-300">Disetujui</span>
                                        @elseif ($pendaftar->status == 'conditional')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-900 text-blue-300">Bersyarat</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-900 text-red-300">Ditolak</span>
                                        @endif
                                    </td>
                                    
                                    {{-- ✅ KONTEN KOLOM BARU DITAMBAHKAN --}}
                                    <td class="px-6 py-4">
                                        @if ($pendaftar->status == 'approved')
                                            @if ($pendaftar->konfirmasi_at)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-900 text-green-300">
                                                    <i class="fas fa-check"></i> Dikonfirmasi
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-700 text-gray-300">
                                                    Belum Konfirmasi
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-600">-</span>
                                        @endif
                                    </td>

                                    {{-- TOMBOL DETAIL & TINDAKAN (Hanya Admin) --}}
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-6 py-4">
                                            <a href="{{ route('daftar.show', $pendaftar) }}" class="font-medium text-[#d97757] hover:underline">
                                                Detail & Tindakan
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr class="border-b border-[#3a3a3a]">
                                    {{-- ✅ Colspan diubah dari 5 menjadi 6 (atau 4 menjadi 5) --}}
                                    <td colspan="{{ auth()->user()->isAdmin() ? '6' : '5' }}" class="text-center py-6 text-gray-500">
                                        Belum ada data pendaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    {{-- Script untuk auto-hide alert --}}
    <script>
        const successAlert = document.querySelector('.success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add('fade-out');
                setTimeout(() => {
                    successAlert.remove();
                }, 500); 
            }, 3000);
        }
    </script>
    @endpush
</x-main-layout>