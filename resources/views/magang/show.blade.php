{{-- resources/views/magang/show.blade.php --}}
<x-main-layout>
    <div class="claude-container">
        
        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('magang.index') }}" class="text-gray-400 hover:text-white" title="Kembali">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="claude-title text-2xl text-white">
                        Detail Data Magang
                    </h2>
                </div>
            </div>
        </div>

        {{-- Grid Utama (Info di kiri, Foto & Aksi di kanan) --}}
        <div class="max-w-7xl mx-auto py-8 px-6">
            
            {{-- Menampilkan Pesan Sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-900 border border-green-700 text-green-200 px-4 py-3 rounded-lg relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @php
                // Logika status dipindahkan ke atas agar bisa dipakai di beberapa tempat
                $now = \Carbon\Carbon::now();
                $mulai = \Carbon\Carbon::parse($magang->tanggal_mulai);
                $selesai = \Carbon\Carbon::parse($magang->tanggal_selesai);

                if ($now->lt($mulai)) {
                    $statusText = 'Belum Mulai';
                    $statusBadgeClass = 'bg-yellow-900 text-yellow-300';
                    $statusIcon = 'fa-hourglass-start';
                } elseif ($now->between($mulai, $selesai)) {
                    $statusText = 'Sedang Magang';
                    $statusBadgeClass = 'bg-green-900 text-green-300';
                    $statusIcon = 'fa-play-circle';
                } else {
                    $statusText = 'Selesai';
                    $statusBadgeClass = 'bg-gray-700 text-gray-300'; // Menggunakan gray-700 untuk konsistensi
                    $statusIcon = 'fa-check-circle';
                }
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Info & Kesan Pesan --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Box Info Detail --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="claude-title text-2xl text-white mb-1">{{ $magang->nama }}</h3>
                            <div class="mb-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusBadgeClass }}">
                                    <i class="fas {{ $statusIcon }} fa-fw mr-1"></i>
                                    {{ $statusText }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <dt class="text-gray-400"><i class="fas fa-university fa-fw mr-1"></i> Asal Kampus</dt>
                                    <dd class="text-white font-medium">{{ $magang->asal_kampus }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-400"><i class="fas fa-calendar-alt fa-fw mr-1"></i> Periode Magang</dt>
                                    <dd class="text-white font-medium">{{ $magang->periode_bulan }} Bulan</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-400"><i class="fas fa-play-circle fa-fw mr-1"></i> Tanggal Mulai</dt>
                                    <dd class="text-white font-medium">{{ $mulai->isoFormat('D MMMM YYYY') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-400"><i class="fas fa-stop-circle fa-fw mr-1"></i> Tanggal Selesai</dt>
                                    <dd class="text-white font-medium">{{ $selesai->isoFormat('D MMMM YYYY') }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-gray-400"><i class="fas fa-clock fa-fw mr-1"></i> Dibuat</dt>
                                    <dd class="text-white font-medium text-xs">{{ $magang->created_at->isoFormat('D MMM YYYY, HH:mm') }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-gray-400"><i class="fas fa-sync-alt fa-fw mr-1"></i> Diupdate</dt>
                                    <dd class="text-white font-medium text-xs">{{ $magang->updated_at->isoFormat('D MMM YYYY, HH:mm') }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Box Kesan & Pesan --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="claude-title text-xl text-white mb-4">Kesan & Pesan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dt class="text-gray-400 text-sm font-medium mb-2"><i class="fas fa-quote-left mr-1"></i> Kesan</dt>
                                    <dd class="text-white mt-1 whitespace-pre-wrap text-sm">
                                        @if($magang->kesan)
                                            {!! nl2br(e($magang->kesan)) !!}
                                        @else
                                            <span class="text-gray-500 italic">Belum ada kesan</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-gray-400 text-sm font-medium mb-2"><i class="fas fa-paper-plane mr-1"></i> Pesan</dt>
                                    <dd class="text-white mt-1 whitespace-pre-wrap text-sm">
                                        @if($magang->pesan)
                                            {!! nl2br(e($magang->pesan)) !!}
                                        @else
                                            <span class="text-gray-500 italic">Belum ada pesan</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Foto, Medsos, Aksi --}}
                <div class="space-y-6">
                    
                    {{-- Box Pas Foto --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="claude-title text-xl text-white mb-4">Foto Profil</h3>
                            @if($magang->foto)
                                <a href="{{ asset('storage/' . $magang->foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $magang->foto) }}" alt="{{ $magang->nama }}"
                                        class="w-full h-64 rounded-lg object-cover object-center border border-[#3a3a3a]">
                                </a>
                            @else
                                <div class="w-full h-64 rounded-lg border border-dashed border-[#3a3a3a] flex items-center justify-center"
                                    style="background: linear-gradient(135deg, #{{ substr(md5($magang->nama), 0, 6) }} 0%, #{{ substr(md5($magang->nama), 6, 6) }} 100%);">
                                    <span class="text-7xl font-bold text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3)">
                                        {{ $magang->initials }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Box Media Sosial & Karya --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            @if($magang->whatsapp || $magang->instagram || $magang->tiktok)
                                <h3 class="claude-title text-xl text-white mb-4">Media Sosial</h3>
                                <div class="flex flex-col gap-3">
                                    @if($magang->whatsapp && auth()->check() && auth()->user()->role == 'admin')
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $magang->whatsapp) }}" target="_blank"
                                            class="filter-btn filter-btn-secondary justify-start">
                                            <i class="fab fa-whatsapp fa-fw w-4"></i>
                                            <span>WhatsApp</span>
                                        </a>
                                    @endif
                                    @if($magang->instagram)
                                        <a href="https://instagram.com/{{ ltrim($magang->instagram, '@') }}" target="_blank"
                                            class="filter-btn filter-btn-secondary justify-start">
                                            <i class="fab fa-instagram fa-fw w-4"></i>
                                            <span>{{ $magang->instagram }}</span>
                                        </a>
                                    @endif
                                    @if($magang->tiktok)
                                        <a href="https://tiktok.com/@{{ ltrim($magang->tiktok, '@') }}" target="_blank"
                                            class="filter-btn filter-btn-secondary justify-start">
                                            <i class="fab fa-tiktok fa-fw w-4"></i>
                                            <span>{{ $magang->tiktok }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            @if($magang->link_pekerjaan)
                                @if($magang->whatsapp || $magang->instagram || $magang->tiktok)
                                   <hr class="border-gray-600 my-4"> {{-- Pemisah jika ada medsos --}}
                                @endif
                                <h3 class="claude-title text-xl text-white mb-4">Karya/Pekerjaan</h3>
                                <a href="{{ $magang->link_pekerjaan }}" target="_blank" class="filter-btn filter-btn-secondary w-full justify-center">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Lihat Karya</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Box Aksi Admin --}}
                    @if(auth()->user()->isAdmin())
                        <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                            <div class="p-6">
                                <h3 class="claude-title text-xl text-white">Tindakan Admin</h3>
                            </div>
                            
                            {{-- Tombol Aksi --}}
                            <div class="bg-[#1a1a1a]/50 px-6 py-4 border-t border-[#3a3a3a] flex flex-col gap-3">
                                <a href="{{ route('magang.edit', $magang) }}"
                                   class="filter-btn bg-blue-700 hover:bg-blue-600 text-white shadow-lg shadow-blue-600/30 w-full justify-center">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Data</span>
                                </a>
                                <form action="{{ route('magang.destroy', $magang) }}" method="POST"
                                      class="w-full"
                                      onsubmit="return confirm('Yakin ingin menghapus data {{ $magang->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="filter-btn bg-red-700 hover:bg-red-600 text-white shadow-lg shadow-red-600/30 w-full justify-center">
                                        <i class="fas fa-trash"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-main-layout>