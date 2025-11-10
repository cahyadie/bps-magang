<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Magang - BPS Bantul</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@400;500;600&display=swap');

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .claude-title {
            font-family: 'Fraunces', 'Georgia', 'Times New Roman', serif;
            font-weight: 400;
            letter-spacing: -0.02em;
        }

        body {
            background-color: #1a1a1a;
            color: #e8e8e8;
        }

        .claude-input {
            background-color: #2a2a2a;
            border: 1px solid #3a3a3a;
            color: #e8e8e8;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            width: 100%;
            transition: all 0.2s;
        }

        .claude-input:focus {
            outline: none;
            border-color: #d97757;
            background-color: #2d2d2d;
        }

        .claude-label {
            display: block;
            color: #c4c4c4;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .claude-button {
            background-color: #d97757;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .claude-button:hover {
            background-color: #e88968;
            transform: translateY(-1px);
        }

        .claude-button-secondary {
            background-color: #3a3a3a;
            color: #e8e8e8;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .claude-button-secondary:hover {
            background-color: #4a4a4a;
        }

        .preview-image {
            max-width: 250px;
            border-radius: 12px;
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .social-input-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .social-icon {
            font-size: 1.5rem;
            min-width: 24px;
        }
    </style>
</head>

<body>
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('magang.show', $magang) }}"
                    class="text-[#d97757] hover:text-[#e88968] inline-flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Detail</span>
                </a>
                <h1 class="claude-title text-3xl text-white">Edit Data Magang</h1>
                <p class="text-[#9ca3af] mt-2">Perbarui informasi peserta magang</p>
            </div>

            <!-- Form Card -->
            <div style="background-color: #2a2a2a; border: 1px solid #3a3a3a; border-radius: 16px; padding: 2rem;">
                <form action="{{ route('magang.update', $magang) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama -->
                    <div class="mb-6">
                        <label for="nama" class="claude-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $magang->nama) }}"
                            class="claude-input @error('nama') border-red-500 @enderror" required>
                        @error('nama')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div class="mb-6">
                        <label for="foto" class="claude-label">Foto Profil</label>

                        @if($magang->foto)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $magang->foto) }}" alt="Foto saat ini"
                                    class="preview-image">
                                <p class="text-[#7a7a7a] text-xs mt-2">Foto saat ini</p>
                            </div>
                        @endif

                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                            class="claude-input @error('foto') border-red-500 @enderror" onchange="previewImage(event)">
                        <p class="text-[#7a7a7a] text-xs mt-2">Kosongkan jika tidak ingin mengubah foto | Format: JPG,
                            PNG | Maksimal 2MB</p>
                        <img id="preview"
                            style="max-width: 250px; border-radius: 12px; margin-top: 1rem; display: none;"
                            alt="Preview">
                        @error('foto')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Kampus -->
                    <div class="mb-6">
                        <label for="asal_kampus" class="claude-label">Asal Kampus</label>
                        <input type="text" name="asal_kampus" id="asal_kampus"
                            value="{{ old('asal_kampus', $magang->asal_kampus) }}"
                            class="claude-input @error('asal_kampus') border-red-500 @enderror" required>
                        @error('asal_kampus')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mb-6">
                        <label for="tanggal_mulai" class="claude-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $magang->tanggal_mulai->format('Y-m-d')) }}"
                            class="claude-input @error('tanggal_mulai') border-red-500 @enderror" required>
                        @error('tanggal_mulai')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-6">
                        <label for="tanggal_selesai" class="claude-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $magang->tanggal_selesai->format('Y-m-d')) }}"
                            class="claude-input @error('tanggal_selesai') border-red-500 @enderror" required>
                        @error('tanggal_selesai')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link Karya/Pekerjaan -->
                    <div class="mb-6">
                        <label for="link_pekerjaan" class="claude-label">Link Karya (Opsional)</label>
                        <textarea name="link_pekerjaan" id="link_pekerjaan" rows="3"
                            class="claude-input @error('link_pekerjaan') border-red-500 @enderror"
                            placeholder="Contoh: https://drive.google.com/...">{{ old('link_pekerjaan', $magang->link_pekerjaan) }}</textarea>
                        <p class="text-[#7a7a7a] text-xs mt-2">Masukkan link Google Drive, GitHub, atau portfolio online
                        </p>
                        @error('link_pekerjaan')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SECTION SOSIAL MEDIA -->
                    <div class="border-t border-[#3a3a3a] pt-6 mt-8 mb-6">
                        <h3 class="text-white text-lg font-semibold mb-4">
                            <i class="fas fa-share-alt mr-2"></i>Media Sosial (Opsional)
                        </h3>

                        <!-- WhatsApp -->
                        <div class="mb-6">
                            <label for="whatsapp" class="claude-label">WhatsApp</label>
                            <div class="social-input-wrapper">
                                <i class="fab fa-whatsapp social-icon text-green-500"></i>
                                <input type="text" name="whatsapp" id="whatsapp"
                                    value="{{ old('whatsapp', $magang->whatsapp) }}" placeholder="081234567890"
                                    class="claude-input @error('whatsapp') border-red-500 @enderror">
                            </div>
                            <p class="text-[#7a7a7a] text-xs mt-2">Masukkan nomor tanpa tanda + atau spasi</p>
                            @error('whatsapp')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instagram -->
                        <div class="mb-6">
                            <label for="instagram" class="claude-label">Instagram</label>
                            <div class="social-input-wrapper">
                                <i class="fab fa-instagram social-icon text-pink-500"></i>
                                <input type="text" name="instagram" id="instagram"
                                    value="{{ old('instagram', $magang->instagram) }}" placeholder="@username"
                                    class="claude-input @error('instagram') border-red-500 @enderror">
                            </div>
                            @error('instagram')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- TikTok -->
                        <div class="mb-6">
                            <label for="tiktok" class="claude-label">TikTok</label>
                            <div class="social-input-wrapper">
                                <i class="fab fa-tiktok social-icon text-blue-400"></i>
                                <input type="text" name="tiktok" id="tiktok"
                                    value="{{ old('tiktok', $magang->tiktok) }}" placeholder="@username"
                                    class="claude-input @error('tiktok') border-red-500 @enderror">
                            </div>
                            @error('tiktok')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION KESAN & PESAN ✅ BARU -->
                    <div class="border-t border-[#3a3a3a] pt-6 mt-8 mb-6">
                        <h3 class="text-white text-lg font-semibold mb-4">
                            <i class="fas fa-comment-dots mr-2"></i>Kesan & Pesan
                        </h3>

                        <!-- Kesan -->
                        <div class="mb-6">
                            <label for="kesan" class="claude-label">Kesan Selama Magang</label>
                            <textarea name="kesan" id="kesan" rows="5"
                                class="claude-input @error('kesan') border-red-500 @enderror"
                                placeholder="Tulis kesan Anda selama mengikuti program magang di BPS Bantul...">{{ old('kesan', $magang->kesan) }}</textarea>
                            <p class="text-[#7a7a7a] text-xs mt-2">Maksimal 2000 karakter</p>
                            @error('kesan')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pesan -->
                        <div class="mb-6">
                            <label for="pesan" class="claude-label">Pesan & Saran</label>
                            <textarea name="pesan" id="pesan" rows="5"
                                class="claude-input @error('pesan') border-red-500 @enderror"
                                placeholder="Tulis pesan atau saran untuk BPS Bantul...">{{ old('pesan', $magang->pesan) }}</textarea>
                            <p class="text-[#7a7a7a] text-xs mt-2">Maksimal 2000 karakter</p>
                            @error('pesan')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 justify-end pt-4 border-t border-[#3a3a3a]">
                        <a href="{{ route('magang.show', $magang) }}" class="claude-button-secondary">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="claude-button">
                            <i class="fas fa-save mr-2"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>