<x-app-layout>
    <div class="claude-container">

        {{-- Header Section --}}
        <div class="border-b border-[#3a3a3a] mb-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('daftar.index') }}" class="text-gray-400 hover:text-white transition-colors" title="Kembali">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="claude-title text-xl sm:text-2xl text-white">
                        Form Pendaftaran Magang
                    </h2>
                </div>
            </div>
        </div>

        {{-- Main Form Container --}}
        <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-12">
            <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">

                <form method="POST" action="{{ route('daftar.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">

                        {{-- KOLOM KIRI: Data Diri & Akademik --}}
                        <div class="space-y-6">
                            
                            {{-- Nama --}}
                            <div>
                                <label for="nama_pendaftar" class="claude-label">Nama Lengkap</label>
                                <input type="text" name="nama_pendaftar" id="nama_pendaftar" 
                                       value="{{ old('nama_pendaftar') }}" placeholder="Masukkan nama lengkap Anda"
                                       @class(['claude-input', 'is-invalid' => $errors->has('nama_pendaftar')]) required>
                                @error('nama_pendaftar') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="claude-label">Alamat Email Aktif</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email') }}" placeholder="contoh: emailanda@gmail.com"
                                       @class(['claude-input', 'is-invalid' => $errors->has('email')]) required>
                                <span class="form-helper">Notifikasi akan dikirim ke email ini.</span>
                                @error('email') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- [MODIFIKASI] Agama, Provinsi & Kabupaten (Grid 3 Kolom di layar besar) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Agama (Dropdown) --}}
                                <div class="md:col-span-2">
                                    <label for="agama" class="claude-label">Agama</label>
                                    <select name="agama" id="agama" 
                                            @class(['claude-input', 'is-invalid' => $errors->has('agama')]) required>
                                        <option value="">-- Pilih Agama --</option>
                                        @foreach($agamas as $agama)
                                            <option value="{{ $agama->nama }}" {{ old('agama') == $agama->nama ? 'selected' : '' }}>
                                                {{ $agama->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('agama') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Provinsi (Dropdown + Hidden Input) --}}
                                <div>
                                    <label for="provinsi_dropdown" class="claude-label">Provinsi Domisili</label>
                                    <select id="provinsi_dropdown" 
                                            @class(['claude-input', 'is-invalid' => $errors->has('provinsi')]) required>
                                        <option value="">-- Pilih Provinsi --</option>
                                        @foreach($provinsis as $prov)
                                            {{-- Kita simpan ID di value untuk JS, dan Nama di data-nama untuk Input Hidden --}}
                                            <option value="{{ $prov->id }}" data-nama="{{ $prov->nama }}"
                                                {{ old('provinsi') == $prov->nama ? 'selected' : '' }}>
                                                {{ $prov->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- Input Hidden ini yang akan dikirim ke Controller --}}
                                    <input type="hidden" name="provinsi" id="input_provinsi_nama" value="{{ old('provinsi') }}">
                                    
                                    @error('provinsi') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Kabupaten (Dropdown Dinamis) --}}
                                <div>
                                    <label for="kabupaten_dropdown" class="claude-label">Kabupaten / Kota</label>
                                    <select name="kabupaten" id="kabupaten_dropdown" 
                                            @class(['claude-input', 'is-invalid' => $errors->has('kabupaten')]) required disabled>
                                        <option value="">-- Pilih Provinsi Dahulu --</option>
                                        {{-- Opsi akan diisi oleh JavaScript --}}
                                    </select>
                                    @error('kabupaten') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Alamat Lengkap --}}
                            <div>
                                <label for="alamat" class="claude-label">Alamat Lengkap (Jalan, RT/RW, dsb)</label>
                                <textarea name="alamat" id="alamat" rows="3" placeholder="Masukkan detail alamat jalan, nomor rumah, RT/RW, Kelurahan, Kecamatan"
                                          @class(['claude-input', 'is-invalid' => $errors->has('alamat')]) required>{{ old('alamat') }}</textarea>
                                @error('alamat') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Asal Kampus --}}
                            <div>
                                <label for="asal_kampus" class="claude-label">Asal Kampus</label>
                                <input type="text" name="asal_kampus" id="asal_kampus" 
                                       value="{{ old('asal_kampus') }}" placeholder="Contoh: Universitas Muhammadiyah Yogyakarta"
                                       @class(['claude-input', 'is-invalid' => $errors->has('asal_kampus')]) required>
                                @error('asal_kampus') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Prodi --}}
                            <div>
                                <label for="prodi" class="claude-label">Prodi / Jurusan</label>
                                <input type="text" name="prodi" id="prodi" 
                                       value="{{ old('prodi') }}" placeholder="Contoh: Teknologi Informasi"
                                       @class(['claude-input', 'is-invalid' => $errors->has('prodi')]) required>
                                @error('prodi') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Periode Magang --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_mulai" class="claude-label">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                           value="{{ old('tanggal_mulai') }}"
                                           @class(['claude-input', 'is-invalid' => $errors->has('tanggal_mulai')]) required>
                                    @error('tanggal_mulai') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="tanggal_selesai" class="claude-label">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                           value="{{ old('tanggal_selesai') }}"
                                           @class(['claude-input', 'is-invalid' => $errors->has('tanggal_selesai')]) required>
                                    @error('tanggal_selesai') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: Upload Dokumen --}}
                        <div class="space-y-6">
                            
                            {{-- Surat Permohonan --}}
                            <div>
                                <label for="surat_permohonan" class="claude-label">Surat Permohonan Magang (PDF)</label>
                                <input type="file" name="surat_permohonan" id="surat_permohonan" accept=".pdf"
                                       @class(['claude-input', 'is-invalid' => $errors->has('surat_permohonan')]) required>
                                <span class="form-helper">Hanya file .pdf, maks 2MB.</span>
                                @error('surat_permohonan') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Surat Kampus --}}
                            <div>
                                <label for="surat_kampus" class="claude-label">Surat Keterangan Kampus (PDF)</label>
                                <input type="file" name="surat_kampus" id="surat_kampus" accept=".pdf"
                                       @class(['claude-input', 'is-invalid' => $errors->has('surat_kampus')]) required>
                                <span class="form-helper">Surat resmi dari Universitas/Fakultas. Maks 2MB.</span>
                                @error('surat_kampus') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Pas Foto --}}
                            <div>
                                <label for="pas_foto" class="claude-label">Pas Foto (JPG/PNG)</label>
                                <input type="file" name="pas_foto" id="pas_foto" accept="image/jpeg,image/png,image/jpg"
                                       @class(['claude-input', 'is-invalid' => $errors->has('pas_foto')]) required>
                                <span class="form-helper">Hanya file .jpg, .jpeg, .png, maks 2MB.</span>
                                @error('pas_foto') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="bg-[#1a1a1a]/50 px-4 py-4 sm:px-6 border-t border-[#3a3a3a]">
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:items-center gap-3">
                            <a href="{{ route('daftar.index') }}" 
                               class="claude-button-secondary w-full sm:w-auto justify-center">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="claude-button w-full sm:w-auto justify-center">
                                <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Script AJAX untuk Kabupaten --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsi_dropdown');
            const kabupatenSelect = document.getElementById('kabupaten_dropdown');
            const inputProvinsiNama = document.getElementById('input_provinsi_nama');

            // Fungsi untuk memuat kabupaten
            function loadKabupaten(provinsiId, selectedKabupaten = null) {
                if (!provinsiId) return;

                // Reset state
                kabupatenSelect.innerHTML = '<option value="">Memuat...</option>';
                kabupatenSelect.disabled = true;

                // Fetch data
                fetch('{{ route("ajax.kabupaten") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ provinsi_id: provinsiId })
                })
                .then(response => response.json())
                .then(data => {
                    kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                    
                    // Populate options
                    Object.entries(data).forEach(([key, value]) => {
                        const option = document.createElement('option');
                        option.value = value; // Kita pakai Nama sebagai value
                        option.textContent = value;
                        
                        // Cek jika ada old value (untuk validasi error)
                        if (selectedKabupaten && selectedKabupaten === value) {
                            option.selected = true;
                        }
                        
                        kabupatenSelect.appendChild(option);
                    });

                    kabupatenSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    kabupatenSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
            }

            // Event Listener saat Provinsi berubah
            provinsiSelect.addEventListener('change', function() {
                const provinsiId = this.value;
                const provinsiNama = this.options[this.selectedIndex].getAttribute('data-nama');
                
                // Set input hidden
                inputProvinsiNama.value = provinsiNama ? provinsiNama : '';

                if (provinsiId) {
                    loadKabupaten(provinsiId);
                } else {
                    kabupatenSelect.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
                    kabupatenSelect.disabled = true;
                }
            });

            // Cek apakah ada old value saat halaman dimuat (kasus error validasi)
            const oldProvinsiId = provinsiSelect.value;
            const oldKabupaten = "{{ old('kabupaten') }}";

            if (oldProvinsiId) {
                // Pastikan input hidden terisi
                const selectedOption = provinsiSelect.options[provinsiSelect.selectedIndex];
                if (selectedOption) {
                    inputProvinsiNama.value = selectedOption.getAttribute('data-nama');
                }
                // Load kabupaten kembali
                loadKabupaten(oldProvinsiId, oldKabupaten);
            }
        });
    </script>
    @endpush

</x-app-layout>