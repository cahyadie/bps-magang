<x-app-layout>
    <div class="claude-container">

        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

                    {{-- Judul dan View Toggler --}}
                    <div class="flex items-center gap-4">
                        <h2 class="claude-title text-2xl text-white">Data Magang</h2>

                        <div class="hidden sm:flex items-center bg-black/20 rounded-lg p-1 border border-[#3a3a3a]">
                            <button id="view-grid-btn" class="view-toggle-btn active" title="Tampilan Grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button id="view-row-btn" class="view-toggle-btn" title="Tampilan Scroll">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Tombol Tambah (Admin Only) --}}
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('magang.create') }}"
                            class="claude-button px-5 py-2.5 inline-flex items-center gap-2 w-full sm:w-auto justify-center">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Data</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6">
            {{-- Alert Success --}}
            @if (session('success'))
                <div class="success-alert mb-6">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Filter Container --}}
            <div class="filter-container">
                <form method="GET" action="{{ route('magang.index') }}" class="filter-form" id="filterForm">
                    <div class="filter-grid">

                        {{-- Filter: Search --}}
                        <div class="filter-item">
                            <div class="filter-label"><i class="fas fa-search"></i> <span>Cari Peserta</span></div>
                            <div class="relative">
                                <input type="text" name="search" id="searchInput" class="filter-input"
                                    placeholder="Cari berdasarkan nama..." value="{{ request('search') }}"
                                    autocomplete="off">
                                <div id="searchLoader" class="search-loader" style="display: none;"><i
                                        class="fas fa-spinner fa-spin"></i></div>
                            </div>
                        </div>

                        {{-- Filter: Kampus --}}
                        <div class="filter-item">
                            <div class="filter-label"><i class="fas fa-university"></i> <span>Universitas</span></div>
                            <div class="relative">
                                <select name="kampus" id="kampusSelect" class="filter-select">
                                    <option value="">Semua Kampus</option>
                                    @foreach($kampusList as $kampus)
                                        <option value="{{ $kampus }}" {{ request('kampus') == $kampus ? 'selected' : '' }}>
                                            {{ $kampus }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="kampusLoader" class="select-loader" style="display: none;"><i
                                        class="fas fa-spinner fa-spin"></i></div>
                            </div>
                        </div>

                        {{-- Filter: Tahun --}}
                        <div class="filter-item">
                            <div class="filter-label"><i class="fas fa-calendar"></i> <span>Tahun</span></div>
                            <div class="relative">
                                <select name="year" id="yearSelect" class="filter-select">
                                    <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun
                                    </option>
                                    @foreach($availableYears as $year)
                                        @if($year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="yearLoader" class="select-loader" style="display: none;"><i
                                        class="fas fa-spinner fa-spin"></i></div>
                            </div>
                        </div>

                        {{-- Tombol Reset --}}
                        <div class="filter-item filter-actions">
                            @if(request('search') || request('kampus') || $selectedYear != 'all')
                                <a href="{{ route('magang.index') }}" class="filter-btn filter-btn-secondary">
                                    <i class="fas fa-times"></i> <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Info Filter Aktif --}}
                    @if(request('search') || request('kampus') || $selectedYear != 'all')
                        <div class="filter-info">
                            <i class="fas fa-info-circle"></i>
                            <span>Menampilkan hasil untuk:
                                <strong>
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

            {{-- Hasil Data --}}
            @if (count($magangs) > 0)
                <div id="magang-container" class="flex overflow-x-auto gap-6 py-4 hide-scrollbar">
                    @foreach ($magangs as $magang)
                        <div class="card-wrapper flex-shrink-0 w-80 sm:w-96">
                            {{-- Logic Background --}}
                            @php
                                $bgStyle = $magang->foto
                                    ? "background-image: url('" . asset('storage/' . $magang->foto) . "');"
                                    : "background: linear-gradient(135deg, #" . substr(md5($magang->nama), 0, 6) . " 0%, #" . substr(md5($magang->nama), 6, 6) . " 100%);";
                            @endphp

                            <div class="magang-card" style="{{ $bgStyle }}">
                                {{-- Avatar --}}
                                @if (!$magang->foto)
                                    <div class="initial-avatar">{{ $magang->initials }}</div>
                                @endif

                                <div class="card-overlay"></div>

                                <div class="card-content">
                                    
                                    {{-- HEADER: Nama & Status --}}
                                   <div class="card-header">
                                        <div class="card-name">{{ $magang->nama }}</div>
                                        
                                        {{-- LOGIC SAFEGUARD: Menggunakan Null Coalescing Operator (??) --}}
                                        @php
                                            $status = $magang->status_context ?? [
                                                'class' => 'bg-gray-500 text-white', 
                                                'text' => 'N/A'
                                            ];
                                        @endphp

                                        <div class="status-badge {{ $status['class'] }}">
                                            <span class="status-dot"></span>
                                            {{ $status['text'] }}
                                        </div>
                                    </div>

                                    {{-- FOOTER: Info, Tombol Aksi & Sosmed --}}
                                    <div class="card-footer">
                                        
                                        {{-- Info Text --}}
                                        <div class="card-info">
                                            <div class="info-item">
                                                <div class="info-label">Asal Kampus</div>
                                                <div class="info-value">{{ Str::limit($magang->asal_kampus, 30) }}</div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Periode</div>
                                                <div class="info-value">
                                                    {{ $magang->periode_bulan ?? 'N/A' }} bulan
                                                    <span class="text-xs opacity-75 block font-normal">
                                                        ({{ \Carbon\Carbon::parse($magang->tanggal_mulai)->format('d M Y') }} -
                                                        {{ \Carbon\Carbon::parse($magang->tanggal_selesai)->format('d M Y') }})
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Wrapper Tombol & Sosmed (Sejajar) --}}
                                        <div class="card-actions-wrapper">
                                            
                                            {{-- Tombol Aksi (Kiri) --}}
                                            <div class="card-actions">
                                                <a href="{{ route('magang.show', $magang) }}" class="action-btn detail" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if(auth()->user()->isAdmin() || (isset($magang->user_id) && $magang->user_id == auth()->id()))
                                                    <a href="{{ route('magang.edit', $magang) }}" class="action-btn edit" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endif

                                                @if(auth()->user()->isAdmin())
                                                    {{-- [MODIFIKASI] Tombol Delete dengan Trigger Modal --}}
                                                    <form id="delete-form-{{ $magang->id }}" action="{{ route('magang.destroy', $magang) }}" method="POST" class="inline-flex m-0 p-0">
                                                        @csrf @method('DELETE')
                                                        {{-- Perhatikan class 'trigger-delete-modal' dan 'data-form-id' --}}
                                                        <button type="button" 
                                                                class="action-btn delete trigger-delete-modal" 
                                                                data-form-id="delete-form-{{ $magang->id }}"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            {{-- Social Links (Kanan) --}}
                                            <div class="card-socials">
                                                @if ($magang->whatsapp && auth()->user()->isAdmin())
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $magang->whatsapp) }}" 
                                                    target="_blank" class="social-icon-link wa" title="WhatsApp">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                @endif

                                                @if ($magang->instagram)
                                                    <a href="https://instagram.com/{{ ltrim($magang->instagram, '@') }}" 
                                                    target="_blank" class="social-icon-link ig" title="Instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                @endif

                                                @if ($magang->tiktok)
                                                    <a href="https://tiktok.com/{{ ltrim($magang->tiktok, '@') }}" 
                                                    target="_blank" class="social-icon-link tt" title="TikTok">
                                                        <i class="fab fa-tiktok"></i>
                                                    </a>
                                                @endif
                                            </div>

                                        </div> {{-- End Actions Wrapper --}}
                                    </div> {{-- End Card Footer --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="empty-state mt-6">
                    @if(request('search') || request('kampus') || $selectedYear != 'all')
                        <i class="fas fa-search text-6xl text-[#4a4a4a] mb-4"></i>
                        <h3 class="text-white text-xl font-semibold mb-2">Tidak Ada Hasil</h3>
                        <p class="text-gray-400 mb-8">Tidak ditemukan data dengan kriteria pencarian tersebut</p>
                        <a href="{{ route('magang.index') }}" class="claude-button px-5 py-2.5">
                            <i class="fas fa-arrow-left"></i> Kembali ke Semua Data
                        </a>
                    @else
                        <i class="fas fa-folder-open text-6xl text-[#5a5a5a] mb-4"></i>
                        <p class="text-gray-400 text-lg">Belum ada data magang</p>
                    @endif
                </div>
            @endif

            {{-- Pagination --}}
            @if ($magangs->hasPages())
                <div class="mt-8">
                    {{ $magangs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
        
        {{-- [MODIFIKASI] HTML Custom Modal --}}
        <div id="deleteModal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
            
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-2xl bg-[#2a2a2a] border border-[#4a4a4a] text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" id="modalPanel">
                        <div class="p-6">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-900/20 mb-5 border border-red-900/50">
                                <i class="fas fa-trash-alt text-3xl text-red-500"></i>
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-bold leading-6 text-white" id="modal-title">Hapus Data?</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-400">Data ini akan dihapus permanen dan tidak dapat dikembalikan. Yakin ingin melanjutkan?</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-[#222] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2 border-t border-[#333]">
                            <button type="button" id="confirmDeleteBtn" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors duration-200">
                                Ya, Hapus
                            </button>
                            <button type="button" id="cancelDeleteBtn" class="mt-3 inline-flex w-full justify-center rounded-lg bg-[#3a3a3a] px-3 py-2 text-sm font-semibold text-gray-300 shadow-sm ring-1 ring-inset ring-gray-600 hover:bg-[#4a4a4a] hover:text-white sm:mt-0 sm:w-auto transition-colors duration-200">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Custom Modal --}}

    </div>

    {{-- Script External --}}
    @push('scripts')
        <script src="{{ asset('js/page-magang.js') }}"></script>
    @endpush
</x-app-layout>