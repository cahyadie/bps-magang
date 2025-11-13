{{-- resources/views/magang/index.blade.php (SUDAH DIPERBARUI) --}}

<x-main-layout>
    <div class="claude-container">
        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    
                    {{-- Judul dan View Toggler --}}
                    <div class="flex items-center gap-4">
                        <h2 class="claude-title text-2xl text-white">
                            Data Magang
                        </h2>
                        <div class="flex items-center bg-black/20 rounded-lg p-1 border border-[#3a3a3a]">
                            <button id="view-grid-btn" class="view-toggle-btn active" title="Tampilan Grid">
                                <i class="fas fa-th"></i>
                            </button>
                            <button id="view-row-btn" class="view-toggle-btn" title="Tampilan Scroll">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                    
                    {{-- ✅ Tombol "Tambah Data" DIPINDAH KE SINI (Hanya Admin) --}}
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('magang.create') }}" 
                           class="claude-button px-5 py-2.5 inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Data</span>
                        </a>
                    @endif

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto py-8">
            @if (session('success'))
                <div class="px-6">
                    <div class="success-alert">
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="px-6">
                <div class="filter-container">
                    <form method="GET" action="{{ route('magang.index') }}" class="filter-form" id="filterForm">
                        <div class="filter-grid">
                            <div class="filter-item search-item">
                                <div class="filter-label">
                                    <i class="fas fa-search"></i>
                                    <span>Cari Peserta</span>
                                </div>
                                <div style="position: relative;">
                                    <input 
                                        type="text" 
                                        name="search" 
                                        id="searchInput"
                                        class="filter-input" 
                                        placeholder="Cari berdasarkan nama..."
                                        value="{{ request('search') }}"
                                        autocomplete="off"
                                    >
                                    <div id="searchLoader" class="search-loader" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-item">
                                <div class="filter-label">
                                    <i class="fas fa-university"></i>
                                    <span>Universitas</span>
                                </div>
                                <div style="position: relative;">
                                    <select name="kampus" id="kampusSelect" class="filter-select">
                                        <option value="">Semua Kampus</option>
                                        @foreach($kampusList as $kampus)
                                            <option value="{{ $kampus }}" {{ request('kampus') == $kampus ? 'selected' : '' }}>
                                                {{ $kampus }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="kampusLoader" class="select-loader" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-item filter-actions">
                                @if(request('search') || request('kampus'))
                                    <a href="{{ route('magang.index') }}" class="filter-btn filter-btn-secondary">
                                        <i class="fas fa-times"></i>
                                        <span>Reset</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if(request('search') || request('kampus'))
                            <div class="filter-info">
                                <i class="fas fa-info-circle"></i>
                                <span>
                                    Menampilkan hasil untuk:
                                    @if(request('search'))
                                        <strong>"{{ request('search') }}"</strong>
                                    @endif
                                    @if(request('search') && request('kampus'))
                                        di
                                    @endif
                                    @if(request('kampus'))
                                        <strong>{{ request('kampus') }}</strong>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            @if (count($magangs) > 0)
                <div id="magang-container" class="flex overflow-x-auto gap-6 py-4 hide-scrollbar px-6">
                    @foreach ($magangs as $magang)
                        <div class="card-wrapper flex-shrink-0 w-96">
                            {{-- ... (Seluruh HTML untuk .magang-card Anda) ... --}}
                            <div class="magang-card"
                                @if ($magang->foto) 
                                    style="background-image: url('{{ asset('storage/' . $magang->foto) }}');"
                                @else
                                    style="background: linear-gradient(135deg, #{{ substr(md5($magang->nama), 0, 6) }} 0%, #{{ substr(md5($magang->nama), 6, 6) }} 100%);" 
                                @endif>

                                @if (!$magang->foto)
                                    <div class="initial-avatar">
                                        {{ $magang->initials }}
                                    </div>
                                @endif

                                <div class="card-overlay"></div>
                                <div class="card-blur-bottom"></div>

                                <div class="card-content">
                                    <div class="card-header">
                                        <div class="card-name">{{ $magang->nama }}</div>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $mulai = \Carbon\Carbon::parse($magang->tanggal_mulai);
                                            $selesai = \Carbon\Carbon::parse($magang->tanggal_selesai);

                                            if ($now->lt($mulai)) {
                                                $statusClass = 'belum';
                                                $statusText = 'Belum Mulai';
                                            } elseif ($now->between($mulai, $selesai)) {
                                                $statusClass = 'aktif';
                                                $statusText = 'Aktif';
                                            } else {
                                                $statusClass = 'selesai';
                                                $statusText = 'Selesai';
                                            }
                                        @endphp
                                        <div class="status-badge {{ $statusClass }}">
                                            <span class="status-dot"></span>
                                            {{ $statusText }}
                                        </div>
                                    </div>

                                    <div class="card-spacer"></div>

                                    <div class="card-footer">
                                        <div class="card-info">
                                            <div class="info-item">
                                                <div class="info-label">Asal Kampus</div>
                                                <div class="info-value">{{ $magang->asal_kampus }}</div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Periode</div>
                                                <div class="info-value">
                                                    @php
                                                        $periode = $mulai->diffInMonths($selesai);
                                                        if ($selesai->day > $mulai->day) {
                                                            $periode += 1;
                                                        }
                                                        if ($periode == 0) {
                                                            $periodeText = 'Kurang dari 1 bulan';
                                                        } else {
                                                            $periodeText = $periode . ' bulan';
                                                        }
                                                    @endphp
                                                    {{ $periodeText }}
                                                    <span class="text-xs opacity-75">
                                                        ({{ $mulai->format('d/m/y') }} - {{ $selesai->format('d/m/y') }})
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-actions-row">
                                            <div class="card-actions">
                                                <a href="{{ route('magang.show', $magang) }}" class="action-btn detail" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('magang.edit', $magang) }}" class="action-btn edit" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('magang.destroy', $magang) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn delete" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($magang->whatsapp || $magang->instagram || $magang->tiktok)
                                    <div class="card-socials">
                                        @if ($magang->whatsapp && auth()->check() && auth()->user()->role == 'admin')
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $magang->whatsapp) }}"
                                                target="_blank" class="social-icon-link" title="WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        @endif
                                        @if ($magang->instagram)
                                            <a href="https://instagram.com/{{ ltrim($magang->instagram, '@') }}"
                                                target="_blank" class="social-icon-link" title="Instagram">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        @endif
                                        @if ($magang->tiktok)
                                            <a href="https://tiktok.com/{{ ltrim($magang->tiktok, '@') }}" 
                                                target="_blank" class="social-icon-link" title="TikTok">
                                                <i class="fab fa-tiktok"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    @if(request('search') || request('kampus'))
                        <i class="fas fa-search" style="font-size: 4rem; color: #4a4a4a; margin-bottom: 1rem;"></i>
                        <h3 style="color: white; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem;">
                            Tidak Ada Hasil
                        </h3>
                        <p style="color: #9ca3af; margin-bottom: 2rem;">
                            Tidak ditemukan data dengan kriteria pencarian tersebut
                        </p>
                        <a href="{{ route('magang.index') }}" class="claude-button px-5 py-2.5">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali ke Semua Data</span>
                        </a>
                    @else
                        <svg class="mx-auto h-12 w-12 text-[#5a5a5a] mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-[#9ca3af] text-lg">Belum ada data magang</p>
                        {{-- ✅ Teks di empty state juga diubah --}}
                        <p class="text-[#6a6a6a] text-sm mt-2">
                            @if (auth()->user()->isAdmin())
                                Klik tombol "Tambah Data" di atas untuk mulai menambahkan data
                            @else
                                Belum ada data yang ditambahkan oleh Admin
                            @endif
                        </p>
                    @endif
                </div>
            @endif

            @if ($magangs->hasPages())
                <div class="mt-8">
                    {{ $magangs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>


    {{-- Pindahkan semua JS ke sini --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const kampusSelect = document.getElementById('kampusSelect');
                const searchLoader = document.getElementById('searchLoader');
                const kampusLoader = document.getElementById('kampusLoader');
                const resultsContainer = document.getElementById('magang-container');
                
                const gridBtn = document.getElementById('view-grid-btn');
                const rowBtn = document.getElementById('view-row-btn');
                const body = document.body;

                if (resultsContainer) { 
                    resultsContainer.addEventListener('wheel', function(e) {
                        if (body.classList.contains('view-row') && e.deltaY != 0) {
                            e.preventDefault();
                            resultsContainer.scrollLeft += e.deltaY * 1; 
                        }
                    });
                }

                if (gridBtn && rowBtn) {
                    gridBtn.addEventListener('click', function() {
                        body.classList.add('view-grid');
                        body.classList.remove('view-row');
                        gridBtn.classList.add('active');
                        rowBtn.classList.remove('active');
                    });

                    rowBtn.addEventListener('click', function() {
                        body.classList.add('view-row');
                        body.classList.remove('view-grid');
                        rowBtn.classList.add('active');
                        gridBtn.classList.remove('active');
                    });
                }
                
                let searchTimeout;
                function performFilter() {
                    const searchValue = searchInput.value;
                    const kampusValue = kampusSelect.value;
                    
                    searchLoader.style.display = 'flex';
                    kampusLoader.style.display = 'flex';
                    
                    const params = new URLSearchParams();
                    if (searchValue) params.append('search', searchValue);
                    if (kampusValue) params.append('kampus', kampusValue);
                    
                    fetch(`{{ route('magang.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Perbarui seluruh isi halaman, bukan hanya kontainer
                        const newPageContent = doc.querySelector('.claude-container');
                        if (newPageContent) {
                            document.querySelector('.claude-container').innerHTML = newPageContent.innerHTML;
                        }

                        // Update URL
                        const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
                        window.history.pushState({}, '', newUrl);
                    })
                    .catch(error => {
                        console.error('Filter error:', error);
                        searchLoader.style.display = 'none';
                        kampusLoader.style.display = 'none';
                    });
                }
                
                // Cek jika elemen ada sebelum menambah event listener
                if(searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(performFilter, 800);
                    });
                }
                
                if(kampusSelect) {
                    kampusSelect.addEventListener('change', performFilter);
                }
            });
            
            const successAlert = document.querySelector('.success-alert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.classList.add('fade-out');
                    setTimeout(() => {
                        successAlert.remove();
                    }, 500); 
                }, 3000);
            }

            // ... (Kode particle JS Anda) ...
            let particleCount = 0;
            const maxParticles = 50;
            
            document.addEventListener('mousemove', (e) => {
                if (e.clientX > 256 && particleCount < maxParticles && Math.random() > 0.85) {
                    createParticle(e.clientX, e.clientY);
                }
            });

            function createParticle(x, y) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = x + 'px';
                particle.style.top = y + 'px';
                particle.style.animationDelay = Math.random() * 0.5 + 's';
                particle.style.animationDuration = (Math.random() * 2 + 2) + 's';
                
                document.body.appendChild(particle);
                particleCount++;
                
                setTimeout(() => {
                    particle.remove();
                    particleCount--;
                }, 3000);
            }

            if (window.innerWidth < 768) {
                document.removeEventListener('mousemove', createParticle);
            }
        </script>
    @endpush
</x-main-layout>