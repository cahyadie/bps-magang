{{-- resources/views/daftar/show.blade.php --}}
<x-main-layout>
    <div class="claude-container min-h-screen">
        
        {{-- Header Section --}}
        <div class="sticky top-0 z-10 bg-[#1a1a1a]/95 backdrop-blur-md border-b border-[#3a3a3a] shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('daftar.index') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#2a2a2a] text-gray-400 hover:text-white hover:bg-[#3a3a3a] transition-all duration-200" 
                           title="Kembali">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="claude-title text-xl sm:text-2xl text-white">
                                Detail Pendaftaran
                            </h1>
                            <p class="text-sm text-gray-400 mt-1">{{ $pendaftaran->nama_pendaftar }}</p>
                        </div>
                    </div>
                    
                    {{-- Status Badge di Header --}}
                    <div class="hidden sm:block">
                        @if ($pendaftaran->status == 'pending')
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-yellow-900/50 text-yellow-300 border border-yellow-700">
                                <i class="fas fa-clock"></i> Menunggu Review
                            </span>
                        @elseif ($pendaftaran->status == 'approved')
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-green-900/50 text-green-300 border border-green-700">
                                <i class="fas fa-check-circle"></i> Disetujui
                            </span>
                        @elseif ($pendaftaran->status == 'conditional')
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-blue-900/50 text-blue-300 border border-blue-700">
                                <i class="fas fa-info-circle"></i> Disetujui Bersyarat
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-red-900/50 text-red-300 border border-red-700">
                                <i class="fas fa-times-circle"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            
            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-900/50 border border-red-700 text-red-200 px-4 py-4 rounded-xl backdrop-blur-sm" role="alert">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
                        <div class="flex-1">
                            <strong class="font-bold text-lg">Terjadi Kesalahan</strong>
                            <ul class="mt-2 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-400 mt-1">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Grid Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri: Informasi & Form --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Card: Informasi Pendaftar --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden hover:border-[#4a4a4a] transition-colors duration-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-[#1a1a1a] to-[#2a2a2a] border-b border-[#3a3a3a]">
                            <h3 class="claude-title text-lg text-white flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-400"></i>
                                Informasi Pendaftar
                            </h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                                <div class="group">
                                    <dt class="text-xs uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-2">
                                        <i class="fas fa-user text-gray-600"></i>
                                        Nama Lengkap
                                    </dt>
                                    <dd class="text-white font-medium text-base">{{ $pendaftaran->nama_pendaftar }}</dd>
                                </div>
                                
                                <div class="group">
                                    <dt class="text-xs uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-2">
                                        <i class="fas fa-envelope text-gray-600"></i>
                                        Email
                                    </dt>
                                    <dd class="text-white font-medium text-base break-all">{{ $pendaftaran->email }}</dd>
                                </div>
                                
                                <div class="group">
                                    <dt class="text-xs uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-2">
                                        <i class="fas fa-university text-gray-600"></i>
                                        Asal Kampus
                                    </dt>
                                    <dd class="text-white font-medium text-base">{{ $pendaftaran->asal_kampus }}</dd>
                                </div>
                                
                                <div class="group">
                                    <dt class="text-xs uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-gray-600"></i>
                                        Periode Magang
                                    </dt>
                                    <dd class="text-white font-medium text-base">
                                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('d M Y') }} 
                                        <span class="text-gray-500 mx-1">s/d</span>
                                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('d M Y') }}
                                    </dd>
                                </div>
                                
                                <div class="group sm:col-span-2">
                                    <dt class="text-xs uppercase tracking-wider text-gray-500 mb-1.5 flex items-center gap-2">
                                        <i class="fas fa-info-circle text-gray-600"></i>
                                        Status Pendaftaran
                                    </dt>
                                    <dd>
                                        @if ($pendaftaran->status == 'pending')
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg bg-yellow-900/50 text-yellow-300 border border-yellow-700">
                                                <i class="fas fa-clock"></i> Menunggu Review
                                            </span>
                                        @elseif ($pendaftaran->status == 'approved')
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg bg-green-900/50 text-green-300 border border-green-700">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                        @elseif ($pendaftaran->status == 'conditional')
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-900/50 text-blue-300 border border-blue-700">
                                                <i class="fas fa-info-circle"></i> Disetujui Bersyarat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg bg-red-900/50 text-red-300 border border-red-700">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                
                                @if($pendaftaran->remarks)
                                <div class="group sm:col-span-2 pt-4 border-t border-[#3a3a3a]">
                                    <dt class="text-xs uppercase tracking-wider text-gray-500 mb-2 flex items-center gap-2">
                                        <i class="fas fa-comment-alt text-gray-600"></i>
                                        Catatan Terakhir
                                    </dt>
                                    <dd class="text-gray-300 text-sm leading-relaxed bg-[#1a1a1a]/50 p-4 rounded-lg border border-[#3a3a3a] whitespace-pre-wrap">{{ $pendaftaran->remarks }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    {{-- Card: Form Tindakan Admin --}}
                    @if(in_array($pendaftaran->status, ['pending', 'conditional']))
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden hover:border-[#4a4a4a] transition-colors duration-200">
                        <form method="POST" action="{{ route('daftar.updateStatus', $pendaftaran) }}">
                            @csrf
                            <div class="px-6 py-4 bg-gradient-to-r from-[#1a1a1a] to-[#2a2a2a] border-b border-[#3a3a3a]">
                                <h3 class="claude-title text-lg text-white flex items-center gap-2">
                                    <i class="fas fa-clipboard-check text-green-400"></i>
                                    Form Tindakan Admin
                                </h3>
                                <p class="text-sm text-gray-400 mt-1">Review dan berikan keputusan untuk pendaftaran ini</p>
                            </div>
                            
                            <div class="p-6">
                                <div>
                                    <label for="remarks" class="filter-label mb-2 flex items-start gap-2">
                                        <i class="fas fa-pencil-alt text-gray-500 mt-1"></i>
                                        <span>
                                            Catatan & Keterangan
                                            <span class="text-xs text-gray-500 block mt-0.5">Wajib untuk status Ditolak/Bersyarat, Opsional untuk Disetujui</span>
                                        </span>
                                    </label>
                                    <textarea 
                                        name="remarks" 
                                        id="remarks" 
                                        rows="5" 
                                        class="filter-input resize-y min-h-[120px]" 
                                        placeholder="Contoh:&#10;• Untuk Disetujui: Silakan datang pada hari pertama dengan membawa KTP dan surat pengantar asli.&#10;• Untuk Bersyarat: Mohon melengkapi berkas KTP dan Surat Keterangan dari kampus terlebih dahulu.&#10;• Untuk Ditolak: Periode magang yang diminta sudah penuh. Silakan daftar kembali periode berikutnya.">{{ old('remarks', $pendaftaran->remarks) }}</textarea>
                                    @error('remarks') 
                                        <p class="text-red-400 text-sm mt-2 flex items-center gap-2">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="bg-[#1a1a1a]/80 px-6 py-4 border-t border-[#3a3a3a] flex flex-col sm:flex-row gap-3 justify-end">
                                <button type="submit" name="status" value="rejected" 
                                        class="filter-btn bg-red-700/90 hover:bg-red-600 text-white shadow-lg shadow-red-600/20 hover:shadow-red-600/40 transition-all duration-200 justify-center sm:justify-start">
                                    <i class="fas fa-times-circle"></i> 
                                    <span>Tolak Pendaftaran</span>
                                </button>
                                <button type="submit" name="status" value="conditional" 
                                        class="filter-btn bg-blue-700/90 hover:bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all duration-200 justify-center sm:justify-start">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <span>Setujui Bersyarat</span>
                                </button>
                                <button type="submit" name="status" value="approved" 
                                        class="filter-btn filter-btn-primary shadow-lg hover:shadow-xl transition-all duration-200 justify-center sm:justify-start">
                                    <i class="fas fa-check-circle"></i> 
                                    <span>Setujui & Pindahkan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Dokumen --}}
                <div class="space-y-6">
                    
                    {{-- Card: Pas Foto --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden hover:border-[#4a4a4a] transition-colors duration-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-[#1a1a1a] to-[#2a2a2a] border-b border-[#3a3a3a]">
                            <h3 class="claude-title text-lg text-white flex items-center gap-2">
                                <i class="fas fa-image text-purple-400"></i>
                                Pas Foto
                            </h3>
                        </div>
                        <div class="p-6">
                            <a href="{{ asset('storage/' . $pendaftaran->pas_foto) }}" 
                               target="_blank" 
                               class="block group relative overflow-hidden rounded-lg">
                                <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}" 
                                     alt="Pas Foto {{ $pendaftaran->nama_pendaftar }}" 
                                     class="w-full aspect-[3/4] object-cover rounded-lg transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <span class="text-white text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-search-plus"></i>
                                        Lihat Ukuran Penuh
                                    </span>
                                </div>
                            </a>
                            <a href="{{ route('daftar.downloadFile', [$pendaftaran, 'pas_foto']) }}" 
                               class="filter-btn filter-btn-secondary mt-4 w-full justify-center hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-download"></i> 
                                <span>Download Pas Foto</span>
                            </a>
                        </div>
                    </div>

                    {{-- ✅ PERUBAHAN DI SINI --}}
                    {{-- Card: Surat Permohonan --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden hover:border-[#4a4a4a] transition-colors duration-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-[#1a1a1a] to-[#2a2a2a] border-b border-[#3a3a3a]">
                            <h3 class="claude-title text-lg text-white flex items-center gap-2">
                                <i class="fas fa-file-alt text-orange-400"></i>
                                Surat Permohonan
                            </h3>
                        </div>
                        {{-- Konten p-6 di-set agar tombol langsung muncul --}}
                        <div class="p-6">
                            {{-- DIV EMBED DIHAPUS DARI SINI --}}
                            <a href="{{ route('daftar.downloadFile', [$pendaftaran, 'surat_permohonan']) }}" 
                               {{-- class "mt-4" dihapus agar tombol tidak punya margin atas --}}
                               class="filter-btn filter-btn-secondary w-full justify-center hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-download"></i> 
                                <span>Download Surat Permohonan</span>
                            </a>
                        </div>
                    </div>

                    {{-- ✅ PERUBAHAN DI SINI --}}
                    {{-- Card: Surat Kampus --}}
                    <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-xl overflow-hidden hover:border-[#4a4a4a] transition-colors duration-200">
                        <div class="px-6 py-4 bg-gradient-to-r from-[#1a1a1a] to-[#2a2a2a] border-b border-[#3a3a3a]">
                            <h3 class="claude-title text-lg text-white flex items-center gap-2">
                                <i class="fas fa-file-pdf text-red-400"></i>
                                Surat Keterangan Kampus
                            </h3>
                        </div>
                        {{-- Konten p-6 di-set agar tombol langsung muncul --}}
                        <div class="p-6">
                            {{-- DIV EMBED DIHAPUS DARI SINI --}}
                            <a href="{{ route('daftar.downloadFile', [$pendaftaran, 'surat_kampus']) }}" 
                               {{-- class "mt-4" dihapus --}}
                               class="filter-btn filter-btn-secondary w-full justify-center hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-download"></i> 
                                <span>Download Surat Kampus</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-main-layout>