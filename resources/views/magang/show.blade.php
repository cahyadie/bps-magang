<x-app-layout>
    <div class="claude-container">
        <div class="min-h-screen py-8 px-4">
            <div class="max-w-7xl mx-auto">
                
                {{-- Header & Breadcrumb --}}
                <div class="mb-8">
                    <a href="{{ route('magang.index') }}"
                       class="text-[#d97757] hover:text-[#e88968] inline-flex items-center gap-2 mb-4 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Data Magang</span>
                    </a>
                    <h1 class="claude-title text-3xl text-white">Detail Data Magang</h1>
                </div>

                {{-- Success Alert --}}
                @if (session('success'))
                    <div class="success-alert">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Main Content Card --}}
                <div class="detail-card">
                    
                    {{-- 1. Profile Section --}}
                    <div class="text-center mb-8">
                        @if($magang->foto)
                            <img src="{{ asset('storage/' . $magang->foto) }}" alt="{{ $magang->nama }}"
                                 class="profile-image-lg">
                        @else
                            <div class="initial-avatar-lg"
                                 style="background: linear-gradient(135deg, #{{ substr(md5($magang->nama), 0, 6) }} 0%, #{{ substr(md5($magang->nama), 6, 6) }} 100%);">
                                {{ $magang->initials }}
                            </div>
                        @endif
                    </div>

                    {{-- 2. Name & Status --}}
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold text-white mb-3">{{ $magang->nama }}</h2>
                        
                        {{-- Menggunakan Accessor dari Model (Best Practice) --}}
                        <div class="status-badge {{ $magang->status_context['class'] }}">
                            <span class="status-dot"></span>
                            {{ $magang->status_context['text'] }}
                        </div>
                    </div>

                    {{-- 3. Info Grid --}}
                    <div class="detail-info-grid">
                        <div class="detail-info-item">
                            <div class="info-label"><i class="fas fa-university mr-2 text-[#d97757]"></i>Asal Kampus</div>
                            <div class="info-value">{{ $magang->asal_kampus }}</div>
                        </div>

                        <div class="detail-info-item">
                            <div class="info-label"><i class="fas fa-book-open mr-2 text-[#d97757]"></i>Prodi / Jurusan</div>
                            <div class="info-value">{{ $magang->prodi ?? '-' }}</div>
                        </div>

                        <div class="detail-info-item">
                            <div class="info-label"><i class="fas fa-calendar-alt mr-2 text-[#d97757]"></i>Periode Magang</div>
                            <div class="info-value">{{ $magang->periode_bulan }} Bulan</div>
                        </div>

                        <div class="detail-info-item">
                            <div class="info-label"><i class="fas fa-play-circle mr-2 text-[#d97757]"></i>Tanggal Mulai</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($magang->tanggal_mulai)->isoFormat('D MMMM YYYY') }}</div>
                        </div>

                        <div class="detail-info-item">
                            <div class="info-label"><i class="fas fa-stop-circle mr-2 text-[#d97757]"></i>Tanggal Selesai</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($magang->tanggal_selesai)->isoFormat('D MMMM YYYY') }}</div>
                        </div>
                        
                        {{-- Placeholder untuk grid layout 3 kolom yang rapi --}}
                        <div class="detail-info-item flex items-center justify-center opacity-50 border-dashed">
                            <span class="text-sm text-gray-500">MagNet System</span>
                        </div>
                    </div>

                    {{-- 4. Social Media Section --}}
                    @if($magang->whatsapp || $magang->instagram || $magang->tiktok)
                        <div class="social-badge-container">
                            <h3 class="text-white font-semibold text-lg mb-1 flex items-center gap-2">
                                <i class="fas fa-share-alt text-[#d97757]"></i> Media Sosial
                            </h3>
                            <div class="social-links-wrapper">
                                @if($magang->whatsapp && auth()->check() && auth()->user()->isAdmin())
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $magang->whatsapp) }}" target="_blank" 
                                       class="social-badge whatsapp">
                                        <i class="fab fa-whatsapp text-lg"></i> WhatsApp
                                    </a>
                                @endif
                                @if($magang->instagram)
                                    <a href="https://instagram.com/{{ ltrim($magang->instagram, '@') }}" target="_blank" 
                                       class="social-badge instagram">
                                        <i class="fab fa-instagram text-lg"></i> {{ $magang->instagram }}
                                    </a>
                                @endif
                                @if($magang->tiktok)
                                    <a href="https://tiktok.com/@{{ ltrim($magang->tiktok, '@') }}" target="_blank" 
                                       class="social-badge tiktok">
                                        <i class="fab fa-tiktok text-lg"></i> {{ $magang->tiktok }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- 5. Kesan & Pesan + Karya --}}
                    <div class="social-badge-container mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                                <i class="fas fa-comment-dots text-[#d97757]"></i> Kesan & Pesan
                            </h3>
                            @if($magang->link_pekerjaan)
                                <a href="{{ $magang->link_pekerjaan }}" target="_blank" class="karya-link">
                                    <i class="fas fa-external-link-alt"></i> Lihat Karya
                                </a>
                            @endif
                        </div>

                        <div class="kesan-pesan-grid">
                            {{-- Kesan --}}
                            <div class="kesan-pesan-box">
                                <div class="kesan-pesan-label">
                                    <i class="fas fa-quote-left text-[#d97757]"></i> Kesan
                                </div>
                                <div class="kesan-pesan-text">
                                    @if($magang->kesan)
                                        {!! nl2br(e($magang->kesan)) !!}
                                    @else
                                        <span class="text-italic-muted">Belum ada kesan yang ditulis.</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Pesan --}}
                            <div class="kesan-pesan-box">
                                <div class="kesan-pesan-label">
                                    <i class="fas fa-paper-plane text-[#d97757]"></i> Pesan
                                </div>
                                <div class="kesan-pesan-text">
                                    @if($magang->pesan)
                                        {!! nl2br(e($magang->pesan)) !!}
                                    @else
                                        <span class="text-italic-muted">Belum ada pesan yang ditulis.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 6. Meta Info (Timestamps) --}}
                    <div class="mt-8 pt-6 border-t border-[#3a3a3a] flex flex-wrap gap-6 text-sm text-[#7a7a7a]">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            Dibuat: {{ $magang->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i>
                            Diupdate: {{ $magang->updated_at->isoFormat('D MMM YYYY, HH:mm') }}
                        </div>
                    </div>

                    {{-- 7. Footer Actions --}}
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-center mt-8 pt-6 border-t border-[#3a3a3a]">
                        <a href="{{ route('magang.index') }}" class="claude-button-secondary w-full md:w-auto justify-center">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>

                        @if(auth()->user()->isAdmin() || (isset($magang->user_id) && $magang->user_id == auth()->id()))
                            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                                <a href="{{ route('magang.edit', $magang) }}" class="claude-button w-full md:w-auto justify-center">
                                    <i class="fas fa-edit"></i> Edit Data
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('magang.destroy', $magang) }}" method="POST" 
                                          class="w-full md:w-auto"
                                          onsubmit="return confirm('Yakin ingin menghapus data {{ $magang->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="claude-button-danger w-full md:w-auto justify-center">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>