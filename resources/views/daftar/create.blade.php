{{-- resources/views/daftar/create.blade.php --}}
<x-main-layout>
    <div class="claude-container">
        
        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-6 py-4">
                {{-- ✅ BUTTON BACK DITAMBAHKAN DI SINI --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('daftar.index') }}" class="text-gray-400 hover:text-white" title="Kembali">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="claude-title text-2xl text-white">
                        Form Pendaftaran Magang
                    </h2>
                </div>
            </div>
        </div>

        {{-- ✅ CONTAINER DIPERLEBAR (max-w-5xl) --}}
        <div class="max-w-5xl mx-auto py-8 px-6">
            
            {{-- Form Container --}}
            <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                
                {{-- FORM --}}
                {{-- Penting: enctype="multipart/form-data" diperlukan untuk upload file --}}
                <form method="POST" action="{{ route('daftar.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ✅ LAYOUT GRID 2 KOLOM DITERAPKAN DI SINI --}}
                    <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">

                        {{-- Kolom Kiri: Data Diri --}}
                        <div class="space-y-6">
                            {{-- Nama Pendaftar --}}
                            <div>
                                <label for="nama_pendaftar" class="filter-label mb-2">Nama Lengkap</label>
                                <input type="text" name="nama_pendaftar" id="nama_pendaftar" class="filter-input" 
                                       placeholder="Masukkan nama lengkap Anda" value="{{ old('nama_pendaftar') }}" required>
                                @error('nama_pendaftar') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="filter-label mb-2">Alamat Email Aktif</label>
                                <input type="email" name="email" id="email" class="filter-input" 
                                       placeholder="contoh: emailanda@gmail.com" value="{{ old('email') }}" required>
                                <span class="text-xs text-gray-400 mt-1">Notifikasi akan dikirim ke email ini.</span>
                                @error('email') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Asal Kampus --}}
                            <div>
                                <label for="asal_kampus" class="filter-label mb-2">Asal Kampus</label>
                                <input type="text" name="asal_kampus" id="asal_kampus" class="filter-input" 
                                       placeholder="Contoh: Universitas Gadjah Mada" value="{{ old('asal_kampus') }}" required>
                                @error('asal_kampus') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Periode Magang (Grid 2 kolom) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_mulai" class="filter-label mb-2">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="filter-input" 
                                           value="{{ old('tanggal_mulai') }}" required style="color-scheme: dark;">
                                    @error('tanggal_mulai') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="tanggal_selesai" class="filter-label mb-2">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="filter-input" 
                                           value="{{ old('tanggal_selesai') }}" required style="color-scheme: dark;">
                                    @error('tanggal_selesai') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Upload Dokumen --}}
                        <div class="space-y-6">
                            {{-- Surat Permohonan (PDF) --}}
                            <div>
                                <label for="surat_permohonan" class="filter-label mb-2">Surat Permohonan Magang (PDF)</label>
                                <input type="file" name="surat_permohonan" id="surat_permohonan" 
                                       class="filter-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#d97757]/20 file:text-[#e88968] hover:file:bg-[#d97757]/40" 
                                       accept=".pdf" required>
                                <span class="text-xs text-gray-400 mt-1">Hanya file .pdf, maks 2MB.</span>
                                @error('surat_permohonan') <span class="block text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="surat_kampus" class="filter-label mb-2">Surat Keterangan/Rekomendasi Kampus (PDF)</label>
                                <input type="file" name="surat_kampus" id="surat_kampus" 
                                       class="filter-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#d97757]/20 file:text-[#e88968] hover:file:bg-[#d97757]/40" 
                                       accept=".pdf" required>
                                <span class="text-xs text-gray-400 mt-1">Surat resmi dari Universitas/Fakultas. Maks 2MB.</span>
                                @error('surat_kampus') <span class="block text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            {{-- Pas Foto --}}
                            <div>
                                <label for="pas_foto" class="filter-label mb-2">Pas Foto (JPG/PNG)</label>
                                <input type="file" name="pas_foto" id="pas_foto" 
                                       class="filter-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#d97757]/20 file:text-[#e88968] hover:file:bg-[#d97757]/40" 
                                       accept="image/jpeg,image/png,image/jpg" required>
                                <span class="text-xs text-gray-400 mt-1">Hanya file .jpg, .jpeg, .png, maks 1MB.</span>
                                @error('pas_foto') <span class="block text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>

                    {{-- Tombol Submit --}}
                    <div class="bg-[#1a1a1a]/50 px-6 py-4 border-t border-[#3a3a3a] text-right">
                        <a href="{{ route('daftar.index') }}" class="filter-btn filter-btn-secondary mr-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="filter-btn filter-btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-main-layout>