{{-- resources/views/daftar/index.blade.php --}}
<x-main-layout>
    <div class="claude-container">
        
        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-6 py-4">
                {{-- Dibuat flex-col di mobile, row di desktop --}}
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <h2 class="claude-title text-2xl text-white w-full md:w-auto">
                        Pendaftaran Magang
                    </h2>
                    
                    {{-- Wrapper untuk filter + button --}}
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        
                        {{-- ================================= --}}
                        {{-- FORM FILTER TAHUN DITAMBAHKAN   --}}
                        {{-- ================================= --}}
                        <form method="GET" action="{{ route('daftar.index') }}" class="flex-grow flex items-center gap-2">
                            <label for="year" class="filter-label mb-0 text-sm text-gray-400 whitespace-nowrap">Tahun:</label>
                            {{-- Gunakan style 'filter-select' dari main.blade.php --}}
                            <select name="year" id="year" class="filter-select w-full" onchange="this.form.submit()">
                                <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                                @foreach($availableYears as $year)
                                    @if($year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            
                            @if($selectedYear != 'all')
                                {{-- Tombol reset filter --}}
                                <a href="{{ route('daftar.index', ['year' => 'all']) }}" class="text-gray-400 hover:text-white text-lg" title="Reset Tahun">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            @endif
                        </form>
                        {{-- ================================= --}}
                        {{-- AKHIR FORM FILTER               --}}
                        {{-- ================================= --}}

                        <a href="{{ route('daftar.create') }}" 
                           class="claude-button px-4 py-2.5 inline-flex items-center gap-2 flex-shrink-0">
                            <i class="fas fa-user-plus"></i>
                            {{-- Teks disembunyikan di layar yg sangat kecil --}}
                            <span class="hidden sm:inline">Daftar</span> 
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="max-w-7xl mx-auto py-8 px-6">
            
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="px-0 sm:px-6 mb-4">
                    <div class="success-alert">
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- =============================================== --}}
            {{-- VERSI TABEL (HANYA UNTUK DESKTOP / LAYAR BESAR) --}}
            {{-- =============================================== --}}
            <div class="hidden lg:block bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-[#1a1a1a]/50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama Pendaftar</th>
                                <th scope="col" class="px-6 py-3">Asal Kampus</th>
                                <th scope="col" class="px-6 py-3">Periode</th>
                                <th scope="col" class="px-6 py-3">Status</th>
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
                                    <td class="px-6 py-4">
                                        @if ($pendaftar->status == 'approved')
                                            @if ($pendaftar->konfirmasi_at)
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-900 text-green-300">
                                                    <i class="fas fa-check"></i> Dikonfirmasi
                                                </span>
                                            @else
                                                @if(auth()->user()->isAdmin())
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-700 text-gray-300">
                                                        Belum Konfirmasi
                                                    </span>
                                                @else
                                                    <a href="{{ route('daftar.konfirmasi', $pendaftar) }}" 
                                                       class="claude-button inline-flex items-center gap-2 px-3 py-1.5 text-xs">
                                                        <i class="fas fa-check"></i> Konfirmasi
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                            <span class="text-gray-600">-</span>
                                        @endif
                                    </td>
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
                                    <td colspan="{{ auth()->user()->isAdmin() ? '6' : '5' }}" class="text-center py-6 text-gray-500">
                                        {{-- Pesan disesuaikan dengan filter --}}
                                        @if($selectedYear == 'all')
                                            Belum ada data pendaftar.
                                        @else
                                            Tidak ada data pendaftar untuk tahun {{ $selectedYear }}.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- ========================================== --}}
            {{-- VERSI KARTU (HANYA UNTUK MOBILE)          --}}
            {{-- ========================================== --}}
            <div class="block lg:hidden space-y-4">
                @forelse ($pendaftarans as $pendaftar)
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-4">
                        
                        {{-- Baris Atas: Nama & Status --}}
                        <div class="flex justify-between items-start mb-3 pb-3 border-b border-[#3a3a3a]">
                            <h3 class="font-semibold text-white text-lg leading-tight">
                                {{ $pendaftar->nama_pendaftar }}
                            </h3>
                            <div>
                                @if ($pendaftar->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-900 text-yellow-300">Menunggu</span>
                                @elseif ($pendaftar->status == 'approved')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-900 text-green-300">Disetujui</span>
                                @elseif ($pendaftar->status == 'conditional')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-900 text-blue-300">Bersyarat</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-900 text-red-300">Ditolak</span>
                                @endif
                            </div>
                        </div>

                        {{-- Detail Data (Vertikal) --}}
                        <div class="space-y-3 text-sm">
                            <div>
                                <div class="text-xs text-gray-400 uppercase">Asal Kampus</div>
                                <div class="text-gray-200 font-medium">{{ $pendaftar->asal_kampus }}</div>
                            </div>
                            
                            <div>
                                <div class="text-xs text-gray-400 uppercase">Periode</div>
                                <div class="text-gray-200 font-medium whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($pendaftar->tanggal_mulai)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($pendaftar->tanggal_selesai)->format('d/m/Y') }}
                                </div>
                            </div>

                            <div>
                                <div class="text-xs text-gray-400 uppercase">Konfirmasi Kehadiran</div>
                                <div class="text-gray-200 font-medium">
                                    @if ($pendaftar->status == 'approved')
                                        @if ($pendaftar->konfirmasi_at)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-900 text-green-300">
                                                <i class="fas fa-check"></i> Dikonfirmasi
                                            </span>
                                        @else
                                            @if(auth()->user()->isAdmin())
                                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-700 text-gray-300">
                                                    Belum Konfirmasi
                                                </span>
                                            @else
                                                <a href="{{ route('daftar.konfirmasi', $pendaftar) }}" 
                                                   class="claude-button inline-flex items-center gap-2 px-3 py-1.5 text-xs mt-1">
                                                    <i class="fas fa-check"></i> Konfirmasi
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi (Hanya Admin) --}}
                        @if(auth()->user()->isAdmin())
                            <div class="mt-4 pt-4 border-t border-[#3a3a3a] text-right">
                                <a href="{{ route('daftar.show', $pendaftar) }}" class="font-medium text-[#d97757] hover:underline text-sm">
                                    Detail & Tindakan <i class="fas fa-arrow-right text-xs ml-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    {{-- Status Kosong untuk Mobile --}}
                    <div class="text-center py-10 text-gray-500 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl">
                        {{-- Pesan disesuaikan dengan filter --}}
                        @if($selectedYear == 'all')
                            Belum ada data pendaftar.
                        @else
                            Tidak ada data pendaftar untuk tahun {{ $selectedYear }}.
                        @endif
                    </div>
                @endforelse
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
                    successAlert.parentElement.remove(); // Hapus div pembungkusnya
                }, 500); 
            }, 3000);
        }
    </script>
    @endpush
</x-main-layout>