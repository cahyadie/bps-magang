<x-app-layout>
    <div class="claude-container">
        
        {{-- Header Page --}}
        <div class="border-b border-[#3a3a3a] mb-8">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center gap-4">
                <a href="{{ route('magang.index') }}" class="text-gray-400 hover:text-white transition-colors" title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="claude-title text-2xl text-white">Tambah Data Magang</h2>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-6 pb-12">
            <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg overflow-hidden">
                
                <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            {{-- COLUMN LEFT: Personal Info --}}
                            <div class="space-y-6">
                                {{-- Nama --}}
                                <div>
                                    <label for="nama" class="claude-label">Nama Lengkap</label>
                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
                                           @class(['claude-input', 'is-invalid' => $errors->has('nama')]) required>
                                    @error('nama') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Kampus --}}
                                <div>
                                    <label for="asal_kampus" class="claude-label">Asal Kampus</label>
                                    <input type="text" name="asal_kampus" id="asal_kampus" value="{{ old('asal_kampus') }}" 
                                           @class(['claude-input', 'is-invalid' => $errors->has('asal_kampus')]) required>
                                    @error('asal_kampus') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Prodi --}}
                                <div>
                                    <label for="prodi" class="claude-label">Prodi / Jurusan</label>
                                    <input type="text" name="prodi" id="prodi" value="{{ old('prodi') }}" 
                                           placeholder="Contoh: Informatika"
                                           @class(['claude-input', 'is-invalid' => $errors->has('prodi')]) required>
                                    @error('prodi') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Tanggal Mulai --}}
                                <div>
                                    <label for="tanggal_mulai" class="claude-label">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" 
                                           class="claude-input" style="color-scheme: dark;" required>
                                    @error('tanggal_mulai') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Tanggal Selesai --}}
                                <div>
                                    <label for="tanggal_selesai" class="claude-label">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" 
                                           class="claude-input" style="color-scheme: dark;" required>
                                    @error('tanggal_selesai') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Link Karya --}}
                                <div>
                                    <label for="link_pekerjaan" class="claude-label">Link Karya (Opsional)</label>
                                    <textarea name="link_pekerjaan" id="link_pekerjaan" rows="3" 
                                              placeholder="Contoh: https://drive.google.com/..."
                                              @class(['claude-input', 'is-invalid' => $errors->has('link_pekerjaan')])>{{ old('link_pekerjaan') }}</textarea>
                                    <p class="helper-text">Masukkan link Google Drive, GitHub, atau portfolio online</p>
                                    @error('link_pekerjaan') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- COLUMN RIGHT: Photo & Social --}}
                            <div class="space-y-6">
                                {{-- Foto Profil --}}
                                <div>
                                    <label for="foto" class="claude-label">Foto Profil</label>
                                    <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg" 
                                           onchange="previewImage(event)"
                                           @class(['claude-input', 'is-invalid' => $errors->has('foto')]) required>
                                    <p class="helper-text">Format: JPG, PNG | Maksimal 2MB</p>
                                    
                                    {{-- Image Preview Container --}}
                                    <img id="preview" class="preview-image" alt="Preview Foto">
                                    
                                    @error('foto') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                {{-- Divider --}}
                                <div class="border-t border-[#3a3a3a] pt-6 mt-2">
                                    <h3 class="claude-title text-xl text-white mb-4">
                                        <i class="fas fa-share-alt mr-2 text-[#d97757]"></i>Media Sosial
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        {{-- WhatsApp --}}
                                        <div>
                                            <label for="whatsapp" class="claude-label">WhatsApp</label>
                                            <div class="social-input-wrapper">
                                                <i class="fab fa-whatsapp social-icon text-green-500"></i>
                                                {{-- Input Validation on Input --}}
                                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" 
                                                       placeholder="081234567890"
                                                       oninput="validatePhone(this)"
                                                       inputmode="numeric"
                                                       @class(['claude-input', 'is-invalid' => $errors->has('whatsapp')])>
                                            </div>
                                            {{-- Error Server --}}
                                            @error('whatsapp') <p class="error-message ml-9">{{ $message }}</p> @enderror
                                            
                                            {{-- Error Client (JS) --}}
                                            <p id="wa-error" class="error-message ml-9" style="display: none;">
                                                Hanya boleh diisi dengan angka!
                                            </p>
                                        </div>

                                        {{-- Instagram --}}
                                        <div>
                                            <label for="instagram" class="claude-label">Instagram</label>
                                            <div class="social-input-wrapper">
                                                <i class="fab fa-instagram social-icon text-pink-500"></i>
                                                <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}" 
                                                       placeholder="@username"
                                                       @class(['claude-input', 'is-invalid' => $errors->has('instagram')])>
                                            </div>
                                            @error('instagram') <p class="error-message ml-9">{{ $message }}</p> @enderror
                                        </div>

                                        {{-- TikTok --}}
                                        <div>
                                            <label for="tiktok" class="claude-label">TikTok</label>
                                            <div class="social-input-wrapper">
                                                <i class="fab fa-tiktok social-icon text-blue-400"></i>
                                                <input type="text" name="tiktok" id="tiktok" value="{{ old('tiktok') }}" 
                                                       placeholder="@username"
                                                       @class(['claude-input', 'is-invalid' => $errors->has('tiktok')])>
                                            </div>
                                            @error('tiktok') <p class="error-message ml-9">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FULL WIDTH: Kesan & Pesan --}}
                        <div class="border-t border-[#3a3a3a] pt-6 mt-8">
                            <h3 class="claude-title text-xl text-white mb-4">
                                <i class="fas fa-comment-dots mr-2 text-[#d97757]"></i>Kesan & Pesan
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="kesan" class="claude-label">Kesan Selama Magang</label>
                                    <textarea name="kesan" id="kesan" rows="4" 
                                              placeholder="Ceritakan pengalaman Anda..."
                                              @class(['claude-input', 'is-invalid' => $errors->has('kesan')])>{{ old('kesan') }}</textarea>
                                    @error('kesan') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label for="pesan" class="claude-label">Pesan & Saran</label>
                                    <textarea name="pesan" id="pesan" rows="4" 
                                              placeholder="Saran untuk BPS Bantul kedepannya..."
                                              @class(['claude-input', 'is-invalid' => $errors->has('pesan')])>{{ old('pesan') }}</textarea>
                                    @error('pesan') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="flex gap-3 justify-end pt-8 mt-4 border-t border-[#3a3a3a]">
                            <a href="{{ route('magang.index') }}" class="claude-button-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="claude-button">
                                <i class="fas fa-save"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }

        // --- VALIDASI ANGKA (Border Merah + Error Text) ---
        function validatePhone(input) {
            const errorMsg = document.getElementById('wa-error');
            
            // Cek jika ada input selain angka
            if (/[^0-9]/.test(input.value)) {
                // 1. Hapus karakter selain angka
                input.value = input.value.replace(/[^0-9]/g, '');

                // 2. Beri styling error (merah)
                input.style.borderColor = '#f87171'; 
                input.style.boxShadow = '0 0 0 1px #f87171';

                // 3. Tampilkan pesan error
                if (errorMsg) {
                    errorMsg.style.display = 'block';
                    errorMsg.style.animation = 'none';
                    errorMsg.offsetHeight; // trigger reflow
                    errorMsg.style.animation = 'shake 0.5s ease-in-out';
                }

                // 4. Hilangkan error setelah 2 detik
                clearTimeout(input.errorTimeout);
                input.errorTimeout = setTimeout(() => {
                    input.style.borderColor = '';
                    input.style.boxShadow = '';
                    if (errorMsg) errorMsg.style.display = 'none';
                }, 2000);
            }
        }
    </script>
    @endpush
</x-app-layout>