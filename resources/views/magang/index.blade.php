<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Magang BPS Bantul</title>
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

        .claude-container {
            background-color: #1a1a1a;
            min-height: 100vh;
        }

        .magang-card {
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            height: 500px;
            transition: all 0.3s ease;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .magang-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom,
                    rgba(0, 0, 0, 0) 0%,
                    rgba(0, 0, 0, 0.1) 30%,
                    rgba(0, 0, 0, 0.5) 70%,
                    rgba(0, 0, 0, 0.85) 100%);
        }

        .card-overlay::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.02) 50%,
                    rgba(255, 255, 255, 0.05) 100%);
        }

        .card-blur-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            mask-image: linear-gradient(to bottom,
                    transparent 0%,
                    rgba(0, 0, 0, 0.3) 30%,
                    rgba(0, 0, 0, 0.6) 50%,
                    rgba(0, 0, 0, 0.8) 70%,
                    black 100%);
            -webkit-mask-image: linear-gradient(to bottom,
                    transparent 0%,
                    rgba(0, 0, 0, 0.3) 30%,
                    rgba(0, 0, 0, 0.6) 50%,
                    rgba(0, 0, 0, 0.8) 70%,
                    black 100%);
        }

        @supports (backdrop-filter: blur(20px)) {
            .card-blur-bottom {
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
            }
        }

        .card-content {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .card-name {
            color: white;
            font-size: 1.75rem;
            font-weight: 600;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .status-badge {
            background-color: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.4rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: white;
        }

        .status-badge.aktif .status-dot {
            background-color: #4ade80;
            box-shadow: 0 0 8px #4ade80;
        }

        .status-badge.selesai .status-dot {
            background-color: #9ca3af;
        }

        .status-badge.belum .status-dot {
            background-color: #fbbf24;
        }

        .card-spacer {
            flex: 1;
        }

        .card-footer {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card-info {
            flex: 1;
        }

        .info-item {
            margin-bottom: 0.5rem;
        }

        .info-label {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .info-value {
            color: white;
            font-size: 0.95rem;
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }

        /* Social Media Section */
        .social-media-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.75rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
        }

        .social-icon-link {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
            text-decoration: none;
            position: relative;
        }

        .social-icon-link:hover {
            transform: translateY(-3px) scale(1.1);
        }

        .social-icon-link i {
            font-size: 1.25rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
        }

        .social-whatsapp {
            background: rgba(37, 211, 102, 0.2);
        }

        .social-whatsapp:hover {
            background: rgba(37, 211, 102, 0.3);
        }

        .social-whatsapp i {
            color: #25d366;
        }

        .social-instagram {
            background: rgba(225, 48, 108, 0.2);
        }

        .social-instagram:hover {
            background: rgba(225, 48, 108, 0.3);
        }

        .social-instagram i {
            color: #e1306c;
        }

        .social-tiktok {
            background: rgba(0, 242, 234, 0.2);
        }

        .social-tiktok:hover {
            background: rgba(0, 242, 234, 0.3);
        }

        .social-tiktok i {
            color: #00f2ea;
        }

        /* Action Buttons */
        .card-actions-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .action-btn {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            color: white;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: scale(1.2);
        }

        .action-btn i {
            font-size: 1.25rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
        }

        .action-btn.detail i {
            color: #60a5fa;
        }

        .action-btn.edit i {
            color: #d97757;
        }

        .action-btn.delete i {
            color: #f87171;
        }

        .claude-button {
            background-color: #d97757;
            color: #ffffff;
            transition: all 0.2s;
            border-radius: 8px;
            font-weight: 500;
        }

        .claude-button:hover {
            background-color: #e88968;
            transform: translateY(-1px);
        }

        .success-alert {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .empty-state {
            background-color: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 12px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .initial-avatar {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body>
    <div class="claude-container">
        <div class="border-b border-[#3a3a3a]">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="claude-title text-2xl text-white">
                        Data Magang BPS Bantul
                    </h2>
                    <a href="{{ route('magang.create') }}" class="claude-button px-5 py-2.5">
                        + Tambah Data Magang
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-8">
            @if (session('success'))
                <div class="success-alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (count($magangs) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($magangs as $magang)
                        <div class="magang-card"
                            @if ($magang->foto) style="background-image: url('{{ asset('storage/' . $magang->foto) }}');"
                            @else
                                 style="background: linear-gradient(135deg, #{{ substr(md5($magang->nama), 0, 6) }} 0%, #{{ substr(md5($magang->nama), 6, 6) }} 100%);" @endif>
                            
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
                                                    // Menghitung periode dalam bulan (termasuk bulan terakhir secara penuh)
                                                    $periode = $mulai->diffInMonths($selesai);
                                                    if ($selesai->day > $mulai->day) {
                                                        $periode += 1; // Jika selisih hari lebih dari 0, tambahkan 1 bulan lagi
                                                    }
                                                    if ($periode == 0) {
                                                        // Fallback jika periode kurang dari satu bulan
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

                                    @if ($magang->whatsapp || $magang->instagram || $magang->tiktok)
                                        <div class="social-media-box">
                                            @if ($magang->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $magang->whatsapp) }}"
                                                    target="_blank" class="social-icon-link social-whatsapp"
                                                    title="WhatsApp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            @endif
                                            @if ($magang->instagram)
                                                <a href="https://instagram.com/{{ ltrim($magang->instagram, '@') }}"
                                                    target="_blank" class="social-icon-link social-instagram"
                                                    title="Instagram">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            @endif
                                            @if ($magang->tiktok)
                                                <a href="https://tiktok.com/@{{ ltrim($magang->tiktok, '@') }}" target="_blank"
                                                    class="social-icon-link social-tiktok" title="TikTok">
                                                    <i class="fab fa-tiktok"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="card-actions-row">
                                        <div class="card-actions">
                                            <a href="{{ route('magang.show', $magang) }}" class="action-btn detail"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('magang.edit', $magang) }}" class="action-btn edit"
                                                title="Edit">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <svg class="mx-auto h-12 w-12 text-[#5a5a5a] mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-[#9ca3af] text-lg">Belum ada data magang</p>
                    <p class="text-[#6a6a6a] text-sm mt-2">Klik tombol "Tambah Data Magang" untuk mulai menambahkan data
                    </p>
                </div>
            @endif

            @if ($magangs->hasPages())
                <div class="mt-8">
                    {{ $magangs->links() }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>