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
            background: linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%);
            color: #e8e8e8;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Orbs */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.1;
            animation: float 25s ease-in-out infinite;
            pointer-events: none;
            z-index: 0;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: #d97757;
            top: -15%;
            left: -10%;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: #d97757;
            bottom: -10%;
            right: -10%;
            animation-delay: 8s;
        }

        .orb-3 {
            width: 350px;
            height: 350px;
            background: #d97757;
            top: 40%;
            right: 20%;
            animation-delay: 16s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(50px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-30px, 30px) scale(0.9);
            }
        }

        .claude-container {
            background-color: transparent;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* Header Animation */
        .header-section {
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .magang-card {
            border-radius: 24px;
            overflow: hidden;
            position: relative;
            height: 480px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        /* Staggered animation for cards */
        .magang-card:nth-child(1) { animation-delay: 0.1s; }
        .magang-card:nth-child(2) { animation-delay: 0.2s; }
        .magang-card:nth-child(3) { animation-delay: 0.3s; }
        .magang-card:nth-child(4) { animation-delay: 0.4s; }
        .magang-card:nth-child(5) { animation-delay: 0.5s; }
        .magang-card:nth-child(6) { animation-delay: 0.6s; }
        .magang-card:nth-child(n+7) { animation-delay: 0.7s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .magang-card:hover {
            transition: transform 0.1s ease, box-shadow 0.1s ease;
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
            font-size: 1.5rem;
            font-weight: 600;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .card-socials {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            position: absolute;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 20;
        }

        .card-socials .social-icon-link {
            width: 36px;
            height: 36px;
        }

        .card-socials .social-icon-link i {
            font-size: 1rem;
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
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
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
        .social-icon-link {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
            position: relative;
        }

        .social-icon-link:hover {
            transform: translateY(-3px) scale(1.15);
        }

        .social-icon-link i {
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.6));
            transition: all 0.3s ease;
        }

        .social-icon-link:hover i {
            filter: drop-shadow(0 4px 12px rgba(217, 119, 87, 0.8));
        }

        .social-icon-link i {
            color: white;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: scale(1.25) rotate(5deg);
        }

        .action-btn:active {
            transform: scale(1.1);
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
            background: linear-gradient(135deg, #d97757 0%, #e88968 100%);
            color: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(217, 119, 87, 0.3);
        }

        .claude-button:hover {
            background: linear-gradient(135deg, #e88968 0%, #f09a7a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(217, 119, 87, 0.5);
        }

        .claude-button:active {
            transform: translateY(0);
        }

        .success-alert {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #4ade80;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            animation: slideInDown 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .success-alert::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #4ade80;
            animation: progressBar 3s linear forwards;
        }

        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }

        .success-alert.fade-out {
            animation: fadeOutUp 0.5s ease-out forwards;
        }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .empty-state {
            background-color: rgba(42, 42, 42, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(217, 119, 87, 0.2);
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .empty-state svg {
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .initial-avatar {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.3);
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.5);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .magang-card {
                height: 460px;
            }
        }

        @media (max-width: 768px) {
            .magang-card {
                height: 440px;
            }

            .card-name {
                font-size: 1.35rem;
            }

            .initial-avatar {
                width: 120px;
                height: 120px;
                font-size: 3rem;
            }

            .card-socials {
                bottom: 1rem;
                right: 1rem;
            }

            .card-socials .social-icon-link {
                width: 32px;
                height: 32px;
            }

            .card-socials .social-icon-link i {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 640px) {
            .header-section .flex {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start !important;
            }

            .claude-button {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            .magang-card {
                height: 420px;
            }

            .card-content {
                padding: 1.25rem;
            }

            .card-name {
                font-size: 1.25rem;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.35rem 0.75rem;
            }

            .info-value {
                font-size: 0.875rem;
            }

            .action-btn {
                width: 40px;
                height: 40px;
            }

            .action-btn i {
                font-size: 1.1rem;
            }

            .initial-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .card-socials {
                gap: 0.4rem;
            }

            /* Show social icons by default on mobile */
            .card-socials {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 400px) {
            .magang-card {
                height: 400px;
            }

            .card-content {
                padding: 1rem;
            }

            .card-name {
                font-size: 1.15rem;
            }

            .card-socials .social-icon-link {
                width: 28px;
                height: 28px;
            }

            .card-socials .social-icon-link i {
                font-size: 0.85rem;
            }

            .bg-orb {
                display: none;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Floating particles */
        .particle {
            position: fixed;
            width: 4px;
            height: 4px;
            background: rgba(217, 119, 87, 0.6);
            border-radius: 50%;
            pointer-events: none;
            animation: particleFloat 3s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(50px);
                opacity: 0;
            }
        }

        /* Loading state animation */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
    </style>
</head>

<body>
    <x-navbar />
    <!-- Animated Background Orbs -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <div class="claude-container">
        <div class="border-b border-[#3a3a3a] header-section">
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

                            @if ($magang->whatsapp || $magang->instagram || $magang->tiktok)
                                <div class="card-socials">
                                    @if ($magang->whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $magang->whatsapp) }}"
                                            target="_blank" class="social-icon-link"
                                            title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    @if ($magang->instagram)
                                        <a href="https://instagram.com/{{ ltrim($magang->instagram, '@') }}"
                                            target="_blank" class="social-icon-link"
                                            title="Instagram">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if ($magang->tiktok)
                                        <a href="https://tiktok.com/@{{ ltrim($magang->tiktok, '@') }}" target="_blank"
                                            class="social-icon-link" title="TikTok">
                                            <i class="fab fa-tiktok"></i>
                                        </a>
                                    @endif
                                </div>
                            @endif
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

    <script>
        // Auto hide success alert after 3 seconds
        const successAlert = document.querySelector('.success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add('fade-out');
                setTimeout(() => {
                    successAlert.remove();
                }, 500); // Wait for fade-out animation to complete
            }, 3000);
        }

        // Create floating particles on mouse move
        let particleCount = 0;
        const maxParticles = 50;
        
        document.addEventListener('mousemove', (e) => {
            if (particleCount < maxParticles && Math.random() > 0.85) {
                createParticle(e.clientX, e.clientY);
            }
        });

        function createParticle(x, y) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = x + 'px';
            particle.style.top = y + 'px';
            particle.style.animationDelay = Math.random() * 0.5 + 's';
            particle.style.animationDuration = (Math.random() * 2 + 2) + 's';
            
            document.body.appendChild(particle);
            particleCount++;
            
            setTimeout(() => {
                particle.remove();
                particleCount--;
            }, 3000);
        }

        // Interactive orbs that follow cursor slightly
        const orbs = document.querySelectorAll('.bg-orb');
        
        document.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            orbs.forEach((orb, index) => {
                const speed = (index + 1) * 20;
                const xMove = (x - 0.5) * speed;
                const yMove = (y - 0.5) * speed;
                
                orb.style.transform = `translate(${xMove}px, ${yMove}px)`;
            });
        });

        // Disable particles on mobile for performance
        if (window.innerWidth < 768) {
            document.removeEventListener('mousemove', createParticle);
        }

        // Card light effect that follows cursor
        document.querySelectorAll('.magang-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                // Calculate center position
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                // Calculate rotation based on cursor position
                const rotateX = ((y - centerY) / centerY) * -5; // Tilt up/down (inverted)
                const rotateY = ((x - centerX) / centerX) * 5;  // Tilt left/right
                
                // Apply 3D transform
                card.style.transform = `
                    translateY(-8px) 
                    scale(1.02) 
                    perspective(1000px) 
                    rotateX(${rotateX}deg) 
                    rotateY(${rotateY}deg)
                `;
                
                // Create dynamic box shadow that follows cursor
                const shadowX = (x - rect.width / 2) / 5;
                const shadowY = (y - rect.height / 2) / 5;
                const blur = 50;
                const spread = 0;
                
                card.style.boxShadow = `
                    ${shadowX}px ${shadowY}px ${blur}px ${spread}px rgba(217, 119, 87, 0.4),
                    ${shadowX * 0.5}px ${shadowY * 0.5}px ${blur * 1.5}px ${spread}px rgba(217, 119, 87, 0.2)
                `;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        });
    </script>
</body>

</html>