<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Magang - BPS Bantul</title>
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
            display: none;
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
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('magang.index') }}"
                    class="text-[#d97757] hover:text-[#e88968] inline-flex items-center gap-2 mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
                <h1 class="claude-title text-3xl text-white">Tambah Data Magang</h1>
            </div>

            <!-- Form Card -->
            <div style="background-color: #2a2a2a; border: 1px solid #3a3a3a; border-radius: 16px; padding: 2rem;">
                <form action="{{ route('magang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-6">
                        <label for="nama" class="claude-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                            class="claude-input @error('nama') border-red-500 @enderror" required>
                        @error('nama')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div class="mb-6">
                        <label for="foto" class="claude-label">Foto Profil</label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                            class="claude-input @error('foto') border-red-500 @enderror" onchange="previewImage(event)"
                            required>
                        <p class="text-[#7a7a7a] text-xs mt-2">Format: JPG, PNG | Maksimal 2MB</p>
                        <img id="preview" class="preview-image" alt="Preview">
                        @error('foto')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Kampus -->
                    <div class="mb-6">
                        <label for="asal_kampus" class="claude-label">Asal Kampus</label>
                        <input type="text" name="asal_kampus" id="asal_kampus" value="{{ old('asal_kampus') }}"
                            class="claude-input @error('asal_kampus') border-red-500 @enderror" required>
                        @error('asal_kampus')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mb-6">
                        <label for="tanggal_mulai" class="claude-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                            class="claude-input @error('tanggal_mulai') border-red-500 @enderror" required>
                        @error('tanggal_mulai')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-6">
                        <label for="tanggal_selesai" class="claude-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai') }}"
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
                            placeholder="Contoh: https://drive.google.com/...">{{ old('link_pekerjaan') }}</textarea>
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
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                                    placeholder="081234567890"
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
                                <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}"
                                    placeholder="@username"
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
                                <input type="text" name="tiktok" id="tiktok" value="{{ old('tiktok') }}"
                                    placeholder="@username"
                                    class="claude-input @error('tiktok') border-red-500 @enderror">
                            </div>
                            @error('tiktok')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 justify-end pt-4 border-t border-[#3a3a3a]">
                        <a href="{{ route('magang.index') }}" class="claude-button-secondary">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="claude-button">
                            <i class="fas fa-save mr-2"></i>Simpan Data
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
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
