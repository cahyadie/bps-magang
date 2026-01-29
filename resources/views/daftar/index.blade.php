<x-app-layout>
    <div class="claude-container">

        {{-- ========================================================================
             HEADER SECTION
             ======================================================================== --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    
                    {{-- Judul Halaman --}}
                    <h2 class="claude-title text-2xl text-white">
                        Pendaftaran Magang
                    </h2>

                    {{-- Tombol Daftar Baru --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('daftar.create') }}" class="claude-button px-4 py-2 inline-flex items-center gap-2 text-sm">
                            <i class="fas fa-user-plus"></i>
                            <span>Daftar Baru</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================
             MAIN CONTENT
             ======================================================================== --}}
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6">

            {{-- Alert Success --}}
            @if (session('success'))
                <div class="success-alert mb-6">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- ====================================================================
                 FILTER SECTION
                 ==================================================================== --}}
            <div class="filter-container mb-6">
                <form id="filterForm" method="GET" action="{{ route('daftar.index') }}" class="filter-form">
                    <div class="filter-grid">
                        
                        {{-- 1. Filter Search (Live) --}}
                        <div class="filter-item">
                            <div class="filter-label"><i class="fas fa-search"></i> <span>Cari Nama</span></div>
                            <div class="relative">
                                <input type="text" name="search" id="searchInput" class="filter-input" 
                                       placeholder="Ketik nama pendaftar..." value="{{ request('search') }}" 
                                       autocomplete="off">
                                {{-- Loader Search --}}
                                <div id="searchLoader" class="search-loader" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Filter Kampus --}}
                        <div class="filter-item">
                            <div class="filter-label"><i class="fas fa-university"></i> <span>Asal Kampus</span></div>
                            <div class="relative">
                                <select name="kampus" id="kampusSelect" class="filter-select">
                                    <option value="">Semua Kampus</option>
                                    @foreach($kampusList as $kampus)
                                        <option value="{{ $kampus }}" {{ request('kampus') == $kampus ? 'selected' : '' }}>
                                            {{ $kampus }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Loader Select --}}
                                <div id="kampusLoader" class="select-loader" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Filter Tahun --}}
                        <div class="filter-item">
                            <div class="filter-label"><i class="fas fa-calendar"></i> <span>Tahun</span></div>
                            <div class="relative">
                                <select name="year" id="yearSelect" class="filter-select">
                                    <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                                {{-- Loader Select --}}
                                <div id="yearLoader" class="select-loader" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Tombol Reset --}}
                        <div class="filter-item filter-actions">
                            @if(request('search') || request('kampus') || $selectedYear != 'all')
                                <a href="{{ route('daftar.index') }}" class="filter-btn filter-btn-secondary" id="resetBtn">
                                    <i class="fas fa-times"></i> <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Info Filter Aktif --}}
                    @if(request('search') || request('kampus') || $selectedYear != 'all')
                        <div class="filter-info mt-4 text-sm text-gray-400 bg-[#2a2a2a] p-2 rounded-md inline-flex items-center gap-2 border border-[#3a3a3a]">
                            <i class="fas fa-info-circle text-[#d97757]"></i>
                            <span>Menampilkan hasil untuk:
                                <strong class="text-white">
                                    {{ implode(', ', array_filter([
                                        request('search') ? '"' . request('search') . '"' : null,
                                        request('kampus'),
                                        $selectedYear != 'all' ? 'Tahun ' . $selectedYear : null
                                    ])) }}
                                </strong>
                            </span>
                        </div>
                    @endif
                </form>
            </div>

            {{-- ====================================================================
                 DATA CONTAINER (Target AJAX DOMParser)
                 PENTING: ID "dataContainer" digunakan JS untuk menukar isi halaman
                 ==================================================================== --}}
            <div id="dataContainer" class="relative min-h-[300px] transition-opacity duration-200">
                
                @if ($pendaftarans->count() > 0)
                    
                    {{-- A. TAMPILAN DESKTOP (TABEL) --}}
                    <div class="hidden lg:block table-container w-full shadow-lg rounded-xl overflow-hidden border border-[#3a3a3a]">
                        <div class="overflow-x-auto w-full">
                            <table class="claude-table w-full">
                                <thead class="bg-[#2a2a2a] text-gray-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Nama Pendaftar</th>
                                        <th class="px-6 py-4 text-left">Asal Kampus</th>
                                        <th class="px-6 py-4 text-left">Periode</th>
                                        <th class="px-6 py-4 text-left">Status</th>
                                        <th class="px-6 py-4 text-left">Konfirmasi</th>
                                        {{-- HEADER AKSI MUNCUL UNTUK SEMUA --}}
                                        <th class="px-6 py-4 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#3a3a3a] bg-[#2a2a2a]/40">
                                    @foreach ($pendaftarans as $pendaftar)
                                        <tr class="hover:bg-[#3a3a3a]/50 transition-colors duration-200">
                                            {{-- Nama --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-white">{{ $pendaftar->nama_pendaftar }}</div>
                                                <div class="text-xs text-gray-500">{{ $pendaftar->email }}</div>
                                            </td>
                                            {{-- Kampus --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                                {{ Str::limit($pendaftar->asal_kampus, 25) }}
                                            </td>
                                            {{-- Periode --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-300 text-sm">
                                                {{ \Carbon\Carbon::parse($pendaftar->tanggal_mulai)->format('d M Y') }} -<br>
                                                {{ \Carbon\Carbon::parse($pendaftar->tanggal_selesai)->format('d M Y') }}
                                            </td>
                                            {{-- Status Badge Logic --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusClass = match($pendaftar->status) {
                                                        'approved' => 'bg-green-900 text-green-300 border border-green-700',
                                                        'rejected' => 'bg-red-900 text-red-300 border border-red-700',
                                                        'conditional' => 'bg-yellow-900 text-yellow-300 border border-yellow-700',
                                                        default => 'bg-gray-700 text-gray-300 border border-gray-600',
                                                    };
                                                    $statusLabel = match($pendaftar->status) {
                                                        'approved' => 'Disetujui',
                                                        'rejected' => 'Ditolak',
                                                        'conditional' => 'Bersyarat',
                                                        default => 'Menunggu',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            {{-- Konfirmasi --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($pendaftar->status == 'approved')
                                                    @if ($pendaftar->konfirmasi_at)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300 border border-green-700">
                                                            <i class="fas fa-check-circle mr-1"></i> Hadir
                                                        </span>
                                                    @else
                                                        @if(auth()->user()->isAdmin())
                                                            <span class="text-xs text-gray-500 italic">Belum konfirmasi</span>
                                                        @else
                                                            <a href="{{ route('daftar.konfirmasi', $pendaftar) }}" class="claude-button px-3 py-1.5 text-xs h-8">
                                                                Konfirmasi
                                                            </a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span class="text-gray-600">-</span>
                                                @endif
                                            </td>
                                            
                                            {{-- KOLOM AKSI (LOGIKA BARU) --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center gap-3">
                                                    {{-- 1. Tombol Detail (Semua User) --}}
                                                    <a href="{{ route('daftar.show', $pendaftar) }}" 
                                                       class="text-blue-400 hover:text-blue-300 transition-colors" 
                                                       title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
            
                                                    @if(auth()->user()->isAdmin())
                                                        {{-- 2. Tombol Edit (Hanya Admin) --}}
                                                       
                                                        {{-- 3. Tombol Hapus (Hanya Admin) --}}
                                                        <form action="{{ route('daftar.destroy', $pendaftar->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-400 transition-colors" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- B. TAMPILAN MOBILE (CARDS) --}}
                    <div class="block lg:hidden space-y-4">
                        @foreach ($pendaftarans as $pendaftar)
                            <div class="mobile-list-card bg-[#2a2a2a]/60 border border-[#3a3a3a] rounded-xl p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-3 border-b border-[#3a3a3a] pb-3">
                                    <div>
                                        <h3 class="font-semibold text-white text-lg">{{ $pendaftar->nama_pendaftar }}</h3>
                                        <span class="text-xs text-gray-500">{{ $pendaftar->email }}</span>
                                    </div>
                                    @php
                                        $statusClass = match($pendaftar->status) {
                                            'approved' => 'bg-green-900 text-green-300 border border-green-700',
                                            'rejected' => 'bg-red-900 text-red-300 border border-red-700',
                                            'conditional' => 'bg-yellow-900 text-yellow-300 border border-yellow-700',
                                            default => 'bg-gray-700 text-gray-300 border border-gray-600',
                                        };
                                        $statusLabel = match($pendaftar->status) {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'conditional' => 'Bersyarat',
                                            default => 'Menunggu',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="space-y-2 text-sm text-gray-300">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Kampus:</span>
                                        <span>{{ $pendaftar->asal_kampus }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Mulai:</span>
                                        <span>{{ \Carbon\Carbon::parse($pendaftar->tanggal_mulai)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                
                                {{-- Footer Card Mobile --}}
                                <div class="mt-4 pt-3 border-t border-[#3a3a3a] flex justify-between items-center">
                                    {{-- Tombol Detail (Kiri) --}}
                                    <a href="{{ route('daftar.show', $pendaftar) }}" class="text-[#d97757] font-medium text-sm flex items-center gap-1">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                    {{-- Tombol Kanan (Admin Actions atau Konfirmasi User) --}}
                                    <div class="flex items-center gap-3">
                                        @if(auth()->user()->isAdmin())
                                            {{-- Edit --}}
                                            <a href="{{ route('daftar.edit', $pendaftar) }}" class="text-yellow-500 hover:text-yellow-400 p-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- Hapus --}}
                                            <form action="{{ route('daftar.destroy', $pendaftar->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 p-1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($pendaftar->status == 'approved' && !$pendaftar->konfirmasi_at)
                                            <a href="{{ route('daftar.konfirmasi', $pendaftar) }}" class="claude-button px-3 py-1 text-xs">
                                                Konfirmasi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- C. PAGINATION --}}
                    @if ($pendaftarans->hasPages())
                        <div class="mt-6">
                            {{ $pendaftarans->appends(request()->query())->links() }}
                        </div>
                    @endif

                @else
                    {{-- D. EMPTY STATE --}}
                    <div class="empty-state mt-6 flex flex-col items-center justify-center text-center py-12 bg-[#2a2a2a]/20 rounded-xl border border-[#3a3a3a]">
                        @if(request('search') || request('kampus') || (isset($selectedYear) && $selectedYear != 'all'))
                            <i class="fas fa-search text-6xl text-[#4a4a4a] mb-4"></i>
                            <h3 class="text-white text-xl font-semibold mb-2">Tidak Ada Hasil</h3>
                            <p class="text-gray-400 mb-8">Tidak ditemukan data dengan kriteria pencarian tersebut</p>
                            <a href="{{ route('daftar.index') }}" class="claude-button px-5 py-2.5">
                                <i class="fas fa-arrow-left"></i> Reset Pencarian
                            </a>
                        @else
                            <i class="fas fa-inbox text-6xl text-[#5a5a5a] mb-4"></i>
                            <h3 class="text-white text-xl font-semibold mb-2">Belum Ada Data</h3>
                            <p class="text-gray-400 text-lg">Belum ada pendaftaran magang masuk.</p>
                        @endif
                    </div>
                @endif

            </div> {{-- End #dataContainer --}}

        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/page-daftar.js') }}"></script>
    @endpush
</x-app-layout>