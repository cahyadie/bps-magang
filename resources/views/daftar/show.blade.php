<x-app-layout>
    <div class="claude-container">
        
    {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] mb-8">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
                
                {{-- Bagian KIRI: Tombol Kembali & Judul --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('daftar.index') }}" class="text-gray-400 hover:text-white transition-colors" title="Kembali">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="claude-title text-2xl text-white">
                        Detail Pendaftaran
                    </h2>
                </div>

                {{-- Bagian KANAN: Tombol Export PDF --}}
                <div>
                    <a href="{{ route('daftar.exportPdf', $pendaftaran->id) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-[#d97757] hover:bg-[#c06040] text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-orange-900/20">
                        <i class="fas fa-file-pdf"></i> 
                        <span class="hidden sm:inline">Export PDF</span> 
                    </a>
                </div>

            </div>
        </div>
        

        <div class="max-w-7xl mx-auto px-6 pb-12">
            
            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-900/50 border border-red-500/50 rounded-xl text-red-200 text-sm">
                    <strong class="font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:
                    </strong>
                    <ul class="list-disc list-inside mt-2 opacity-80">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: Informasi Utama & Form Admin --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- 1. Kartu Informasi Pendaftar --}}
                    <div class="glass-panel">
                        <div class="p-6">
                            <h3 class="claude-title text-xl text-white mb-6 flex items-center gap-2">
                                <i class="fas fa-user-circle text-[#d97757]"></i> Informasi Pendaftar
                            </h3>
                            
                            {{-- GRID INFORMASI --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Baris 1: Identitas --}}
                                <div class="dl-group">
                                    <span class="dl-label">Nama Lengkap</span>
                                    <span class="dl-value">{{ $pendaftaran->nama_pendaftar }}</span>
                                </div>
                                <div class="dl-group">
                                    <span class="dl-label">Email</span>
                                    <span class="dl-value">{{ $pendaftaran->email }}</span>
                                </div>

                                {{-- Baris 2: Agama & Akademik --}}
                                <div class="dl-group">
                                    <span class="dl-label">Agama</span>
                                    <span class="dl-value">{{ $pendaftaran->agama ?? '-' }}</span>
                                </div>
                                <div class="dl-group">
                                    <span class="dl-label">Asal Kampus</span>
                                    <span class="dl-value">{{ $pendaftaran->asal_kampus }}</span>
                                </div>

                                {{-- Baris 3: Domisili Wilayah (Provinsi & Kabupaten) --}}
                                <div class="dl-group">
                                    <span class="dl-label">Provinsi Domisili</span>
                                    <span class="dl-value">{{ $pendaftaran->provinsi ?? '-' }}</span>
                                </div>
                                <div class="dl-group">
                                    <span class="dl-label">Kabupaten / Kota</span>
                                    <span class="dl-value font-semibold text-white">{{ $pendaftaran->kabupaten ?? '-' }}</span>
                                </div>

                                {{-- Baris 4: Alamat Lengkap (Full Width) --}}
                                <div class="dl-group md:col-span-2">
                                    <span class="dl-label">Alamat Lengkap</span>
                                    <span class="dl-value">{{ $pendaftaran->alamat ?? '-' }}</span>
                                </div>

                                {{-- Baris 5: Prodi & Periode --}}
                                <div class="dl-group">
                                    <span class="dl-label">Prodi / Jurusan</span>
                                    <span class="dl-value">{{ $pendaftaran->prodi ?? '-' }}</span>
                                </div>
                                <div class="dl-group">
                                    <span class="dl-label">Periode Magang</span>
                                    <span class="dl-value">
                                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('d M Y') }}
                                    </span>
                                </div>
                                
                                {{-- STATUS & CATATAN --}}
                                <div class="dl-group md:col-span-2 border-t border-[#3a3a3a] pt-4 mt-2">
                                    <span class="dl-label">Status Saat Ini</span>
                                    @php
                                        $statusClass = match($pendaftaran->status) {
                                            'approved' => 'bg-green-500/20 text-green-400 border border-green-500/50',
                                            'rejected' => 'bg-red-500/20 text-red-400 border border-red-500/50',
                                            'conditional' => 'bg-orange-500/20 text-orange-400 border border-orange-500/50',
                                            default => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50',
                                        };
                                        
                                        $statusLabel = match($pendaftaran->status) {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            'conditional' => 'Bersyarat',
                                            default => 'Menunggu Konfirmasi',
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mt-1 {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                        
                                        @if($pendaftaran->konfirmasi_at)
                                            <span class="text-xs text-green-400 flex items-center gap-1">
                                                <i class="fas fa-check-double"></i> Terkonfirmasi: {{ \Carbon\Carbon::parse($pendaftaran->konfirmasi_at)->format('d M Y H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($pendaftaran->remarks)
                                    <div class="dl-group md:col-span-2 bg-[#1a1a1a]/40 p-4 rounded-lg border border-[#3a3a3a]">
                                        <span class="dl-label text-orange-400">Catatan dari Admin</span>
                                        <p class="text-gray-300 whitespace-pre-wrap text-sm mt-1">{{ $pendaftaran->remarks }}</p>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- 2. Form Tindakan Admin --}}
                    @if(auth()->user()->isAdmin() && in_array($pendaftaran->status, ['pending', 'conditional']))
                        <div class="glass-panel">
                            <form method="POST" action="{{ route('daftar.updateStatus', $pendaftaran->id) }}">
                                @csrf
                                <div class="p-6">
                                    <h3 class="claude-title text-xl text-white mb-4 flex items-center gap-2">
                                        <i class="fas fa-gavel text-[#d97757]"></i> Tindakan Admin
                                    </h3>
                                    
                                    <div>
                                        <label for="remarks" class="claude-label">Catatan (Opsional untuk Setuju, Wajib untuk Tolak/Syarat)</label>
                                        <textarea name="remarks" id="remarks" rows="4" class="claude-input" 
                                                  placeholder="Tuliskan alasan penolakan, syarat tambahan, atau catatan persetujuan...">{{ old('remarks', $pendaftaran->remarks) }}</textarea>
                                        @error('remarks') <p class="error-message">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                
                                <div class="bg-[#1a1a1a]/50 px-6 py-4 border-t border-[#3a3a3a] flex flex-wrap gap-3 justify-end">
                                    <button type="submit" name="status" value="rejected" class="btn-action-reject px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                        <i class="fas fa-times-circle"></i> Tolak
                                    </button>
                                    
                                    <button type="submit" name="status" value="conditional" class="btn-action-warn px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                                        <i class="fas fa-exclamation-triangle"></i> ACC Bersyarat
                                    </button>
                                    
                                    <button type="submit" name="status" value="approved" class="claude-button px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                        <i class="fas fa-check-circle"></i> Setujui & Pindahkan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>

                {{-- KOLOM KANAN: Dokumen & File --}}
                <div class="space-y-6">
                    
                    {{-- Pas Foto --}}
                    <div class="glass-panel">
                        <div class="p-6 text-center">
                            <h3 class="claude-title text-lg text-white mb-4">Pas Foto</h3>
                            <div class="relative group inline-block">
                                <a href="{{ asset('storage/' . $pendaftaran->pas_foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}" 
                                         alt="Pas Foto" 
                                         class="w-48 h-48 object-cover rounded-lg border-4 border-[#3a3a3a] shadow-lg mx-auto transition-transform group-hover:scale-105">
                                </a>
                            </div>
                            
                            <a href="{{ route('daftar.downloadFile', [$pendaftaran->id, 'pas_foto']) }}" 
                               class="claude-button-secondary w-full flex justify-center items-center gap-2 mt-6 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                                <i class="fas fa-download"></i> Download Foto
                            </a>
                        </div>
                    </div>

                    {{-- Dokumen PDF --}}
                    <div class="glass-panel">
                        <div class="p-6">
                            <h3 class="claude-title text-lg text-white mb-4">Dokumen Pendukung</h3>
                            
                            <div class="space-y-4">
                                {{-- Surat Permohonan --}}
                                <div class="p-3 bg-[#1a1a1a]/40 rounded-lg border border-[#3a3a3a] flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="bg-red-500/20 text-red-400 p-2 rounded">
                                            <i class="fas fa-file-pdf text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm text-gray-200 font-medium truncate">Surat Permohonan</p>
                                            <p class="text-xs text-gray-500">PDF Document</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('daftar.downloadFile', [$pendaftaran->id, 'surat_permohonan']) }}" 
                                       class="text-gray-400 hover:text-white p-2" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>

                                {{-- Surat Kampus --}}
                                <div class="p-3 bg-[#1a1a1a]/40 rounded-lg border border-[#3a3a3a] flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="bg-red-500/20 text-red-400 p-2 rounded">
                                            <i class="fas fa-file-pdf text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm text-gray-200 font-medium truncate">Surat Kampus</p>
                                            <p class="text-xs text-gray-500">PDF Document</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('daftar.downloadFile', [$pendaftaran->id, 'surat_kampus']) }}" 
                                       class="text-gray-400 hover:text-white p-2" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>