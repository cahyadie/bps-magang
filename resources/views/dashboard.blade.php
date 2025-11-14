{{-- resources/views/dashboard.blade.php --}}
<x-main-layout>

    {{-- =========================================== --}}
    {{-- BAGIAN 1: KUMPULAN STYLES (KONDISIONAL) --}}
    {{-- =========================================== --}}
    @push('styles')
        @if($role == 'admin')
            {{-- PUSH STYLE UNTUK DARK MODE KALENDER ADMIN --}}
            <style>
                /* FullCalendar Dark Theme Overrides (Admin) */
                .claude-calendar {
                    color: #d1d5db;
                }

                .fc .fc-toolbar-title {
                    color: #ffffff;
                }

                .fc .fc-button-primary {
                    background-color: #d97757;
                    border-color: #d97757;
                    color: #ffffff;
                }

                .fc .fc-button-primary:hover,
                .fc .fc-button-primary:active {
                    background-color: #e88968;
                    border-color: #e88968;
                }

                .fc .fc-button {
                    background-color: #374151;
                    border-color: #4b5563;
                    color: #d1d5db;
                }

                .fc .fc-daygrid-day.fc-day-today {
                    background-color: rgba(217, 119, 87, 0.1);
                }

                .fc .fc-daygrid-day-number {
                    color: #212121ff !important;
                }

                .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
                    color: #22c55e !important;
                    font-weight: bold;
                }

                .fc-theme-standard .fc-scrollgrid,
                .fc-theme-standard th,
                .fc-theme-standard td {
                    border-color: #3a3a3a;
                }

                .fc .fc-col-header-cell-cushion {
                    color: #90cdf4 !important;
                    /* Contoh: Biru Muda */
                }

                /* CSS UNTUK SEMBUNYIKAN EVENT BAR & TAMPILKAN IKON */
                .fc-event {
                    cursor: help;
                }

                .fc-daygrid-event {
                    background-color: transparent !important;
                    border: none !important;
                    padding: 0 !important;
                    margin-top: 4px;
                    margin-left: 5px;
                    display: inline-flex;
                    width: auto;
                    height: auto;
                }

                .fc-daygrid-event-dot {
                    display: none !important;
                }

                .fc-event-title,
                .fc-event-time {
                    display: none !important;
                }
            </style>
        @elseif($role == 'user')
            {{-- Helper CSS untuk status badge DAN Kalender User --}}
            <style>
                .status-badge {
                    display: inline-flex;
                    align-items: center;
                    padding: 0.5rem 1rem;
                    border-radius: 9999px;
                    font-weight: 600;
                    font-size: 0.875rem;
                }

                .claude-button {
                    background: linear-gradient(135deg, #d97757 0%, #e88968 100%);
                    color: #ffffff;
                    padding: 0.75rem 1.5rem;
                    border-radius: 12px;
                    font-weight: 600;
                    transition: all 0.3s;
                    border: none;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    text-decoration: none;
                    box-shadow: 0 4px 15px rgba(217, 119, 87, 0.3);
                }

                .claude-button:hover {
                    background: linear-gradient(135deg, #e88968 0%, #f09a7a 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(217, 119, 87, 0.4);
                }

                /* CSS DARK MODE KALENDER UNTUK USER */
                .claude-calendar {
                    color: #d1d5db;
                }

                .fc .fc-toolbar-title {
                    color: #ffffffff;
                }

                .fc .fc-button-primary {
                    background-color: #22c55e;
                    border-color: #22c55e;
                    color: #ffffff;
                }

                .fc .fc-button-primary:hover,
                .fc .fc-button-primary:active {
                    background-color: #16a34a;
                    border-color: #16a34a;
                }

                .fc .fc-button {
                    background-color: #374151;
                    border-color: #4b5563;
                    color: #d1d5db;
                }

                .fc .fc-daygrid-day.fc-day-today {
                    background-color: rgba(34, 197, 94, 0.1);
                }

                .fc .fc-daygrid-day-number {
                    color: #191a1aff !important;
                }

                .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
                    color: #e88968 !important;
                    font-weight: bold;
                }

                .fc .fc-col-header-cell-cushion {
                    color: #f3b49f !important;
                    /* Contoh: Oranye Muda */
                }

                /* CSS UNTUK SEMBUNYIKAN EVENT BAR (USER) */
                .fc-event {
                    cursor: help;
                }

                .fc-daygrid-event {
                    background-color: transparent !important;
                    border: none !important;
                    padding: 0 !important;
                    margin-top: 4px;
                    margin-left: 5px;
                    display: inline-flex;
                    width: auto;
                    height: auto;
                }

                .fc-daygrid-event-dot {
                    display: none !important;
                }

                .fc-event-title,
                .fc-event-time {
                    display: none !important;
                }
            </style>
        @endif
    @endpush


    {{-- =========================================== --}}
    {{-- BAGIAN 2: KUMPULAN SCRIPTS (KONDISIONAL) --}}
    {{-- =========================================== --}}
    @push('scripts')
        @if($role == 'admin')
            {{-- SCRIPT UNTUK GRAFIK ADMIN --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // === Bagian Chart.js ===
                    const pendaftaranLabels = @json($pendaftaranChartLabels);
                    const pendaftaranData = @json($pendaftaranChartData);
                    const magangData = @json($magangChartData);
                    const statusData = @json($statusChartData);

                    Chart.defaults.color = '#9ca3af';
                    Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

                    // ✅ 1. KODE GRAFIK PENDAFTARAN (LENGKAP)
                    const ctxPendaftaran = document.getElementById('pendaftaranChart');
                    if (ctxPendaftaran) {
                        new Chart(ctxPendaftaran, {
                            type: 'bar',
                            data: {
                                labels: pendaftaranLabels,
                                datasets: [{
                                    label: 'Jumlah Pendaftar',
                                    data: pendaftaranData,
                                    backgroundColor: 'rgba(217, 119, 87, 0.6)',
                                    borderColor: 'rgba(217, 119, 87, 1)',
                                    borderWidth: 1
                                }]
                            }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                        });
                    }

                    // ✅ 2. KODE GRAFIK STATUS (LENGKAP)
                    const ctxStatus = document.getElementById('statusChart');
                    if (ctxStatus) {
                        new Chart(ctxStatus, {
                            type: 'doughnut',
                            data: {
                                labels: ['Menunggu', 'Disetujui', 'Ditolak', 'Bersyarat'],
                                datasets: [{
                                    label: 'Status Pendaftar',
                                    data: statusData,
                                    backgroundColor: ['rgba(250, 204, 21, 0.7)', 'rgba(74, 222, 128, 0.7)', 'rgba(248, 113, 113, 0.7)', 'rgba(96, 165, 250, 0.7)'],
                                    borderColor: '#3a3a3a',
                                    borderWidth: 2
                                }]
                            }, options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                        });
                    }

                    // ✅ 3. KODE GRAFIK MAGANG (LENGKAP)
                    const ctxMagang = document.getElementById('magangChart');
                    if (ctxMagang) {
                        new Chart(ctxMagang, {
                            type: 'line',
                            data: {
                                labels: pendaftaranLabels, // Menggunakan label yang sama
                                datasets: [{
                                    label: 'Peserta Mulai Magang',
                                    data: magangData,
                                    backgroundColor: 'rgba(96, 165, 250, 0.2)',
                                    borderColor: 'rgba(96, 165, 250, 1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.3
                                }]
                            }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                        });
                    }

                    // === INISIALISASI FULLCALENDAR (ADMIN) ===
                    const calendarEl = document.getElementById('calendar');
                    if (calendarEl) {
                        const calendarEvents = @json($calendarEvents);

                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            locale: 'id',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth' // HANYA TAMPILKAN BULAN
                            },
                            events: calendarEvents,
                            height: 'auto',

                            // FUNGSI eventContent UNTUK MENGGANTI IKON
                            eventContent: function (info) {
                                const props = info.event.extendedProps;
                                const title = info.event.title;
                                let iconClass = '';

                                if (props.icon === 'play') {
                                    // Ikon Mulai
                                    iconClass = 'fas fa-user text-green-400 text-lg'; // IKON USER
                                } else if (props.icon === 'flag') {
                                    // Ikon Selesai
                                    iconClass = 'fas fa-user-check text-red-400 text-lg'; // IKON USER DENGAN CENTANG (agar beda)
                                }

                                if (iconClass) {
                                    let iconEl = document.createElement('i');
                                    iconEl.className = iconClass;
                                    iconEl.setAttribute('title', title);

                                    return { domNodes: [iconEl] };
                                }

                                return false;
                            },
                        });

                        calendar.render();
                    }
                });
            </script>
        @elseif($role == 'user')
            {{-- SCRIPT KALENDER UNTUK USER --}}
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // === INISIALISASI FULLCALENDAR UNTUK USER ===
                    const calendarEl = document.getElementById('calendar');
                    if (calendarEl) {
                        const calendarEvents = @json($calendarEvents); // Ambil data dari controller

                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            locale: 'id',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth' // HANYA TAMPILKAN BULAN
                            },
                            events: calendarEvents,
                            height: 'auto',

                            // FUNGSI eventContent UNTUK MENGGANTI IKON
                            eventContent: function (info) {
                                const props = info.event.extendedProps;
                                const title = info.event.title;
                                let iconClass = '';

                                if (props.icon === 'play') {
                                    iconClass = 'fas fa-user text-green-400 text-lg'; // IKON USER
                                } else if (props.icon === 'flag') {
                                    iconClass = 'fas fa-user-check text-red-400 text-lg'; // IKON USER DENGAN CENTANG
                                }

                                if (iconClass) {
                                    let iconEl = document.createElement('i');
                                    iconEl.className = iconClass;
                                    iconEl.setAttribute('title', title); // Tooltip

                                    return { domNodes: [iconEl] };
                                }

                                return false;
                            },
                        });

                        calendar.render();
                    }
                });
            </script>
        @endif
    @endpush


    {{-- =========================================== --}}
    {{-- BAGIAN 3: TAMPILAN UNTUK ADMIN (HTML) --}}
    {{-- =========================================== --}}
    @if($role == 'admin')

        <div class="claude-container">

            {{-- Header Section dengan Filter Tahun --}}
            <div class="border-b border-[#3a3a3a] header-section">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <h2 class="claude-title text-xl sm:text-2xl text-white">
                            {{ __('Dashboard Admin') }}
                        </h2>

                        {{-- FORM FILTER TAHUN --}}
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <label for="year" class="filter-label mb-0">Tampilkan Tahun:</label>
                            <select name="year" id="year" class="filter-select w-40" onchange="this.form.submit()">
                                <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                                @foreach($availableYears as $year)
                                    @if($year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            @if($selectedYear != 'all')
                                <a href="{{ route('dashboard', ['year' => 'all']) }}"
                                    class="text-gray-400 hover:text-white text-sm" title="Tampilkan Semua Tahun">
                                    <i class="fas fa-times"></i> Tampilkan Semua
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Konten utama halaman admin --}}
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6">

                <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl overflow-hidden shadow-lg mb-6"
                    style="animation: slideDown 0.5s ease-out;">
                    <div class="p-6 text-gray-300">
                        {{ __("You're logged in as Admin!") }}

                        @if($selectedYear && $selectedYear != 'all')
                            <span class="ml-2 text-gray-400">Menampilkan statistik untuk tahun {{ $selectedYear }}.</span>
                        @else
                            <span class="ml-2 text-gray-400">Menampilkan statistik untuk semua tahun.</span>
                        @endif
                    </div>
                </div>

                {{-- KUMPULAN GRAFIK --}}
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                    {{-- ✅ KARTU INI YANG HILANG --}}
                    <div
                        class="lg:col-span-3 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                        <h3 class="claude-title text-xl text-white mb-4">
                            Pendaftaran Masuk ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                        </h3>
                        <canvas id="pendaftaranChart"></canvas>
                    </div>

                    {{-- ✅ KARTU INI YANG HILANG --}}
                    <div
                        class="lg:col-span-2 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                        <h3 class="claude-title text-xl text-white mb-4">
                            Status Pendaftar ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                        </h3>
                        <canvas id="statusChart"></canvas>
                    </div>

                    {{-- ✅ KARTU INI YANG HILANG --}}
                    <div
                        class="lg:col-span-5 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                        <h3 class="claude-title text-xl text-white mb-4">
                            Peserta Mulai Magang ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                        </h3>
                        <canvas id="magangChart"></canvas>
                    </div>

                    {{-- KARTU UNTUK KALENDER --}}
                    <div
                        class="lg:col-span-5 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                        <h3 class="claude-title text-xl text-white mb-4">
                            Kalender Peserta Magang ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                        </h3>
                        <div id="calendar" class="claude-calendar"></div>
                    </div>

                </div>

            </div>
        </div>

        {{-- =========================================== --}}
        {{-- BAGIAN 4: TAMPILAN UNTUK USER BIASA (HTML) --}}
        {{-- =========================================== --}}
    @elseif($role == 'user')

        <div class="claude-container">

            {{-- Header Section Sederhana untuk User --}}
            <div class="border-b border-[#3a3a3a] header-section">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
                    <h2 class="claude-title text-xl sm:text-2xl text-white">
                        {{ __('Dashboard') }}
                    </h2>
                </div>
            </div>

            {{-- Konten utama halaman user --}}
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6">

                {{-- Kartu Welcome --}}
                <div
                    class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl overflow-hidden shadow-lg mb-6">
                    <div class="p-6 text-gray-300">
                        Selamat datang, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!
                    </div>
                </div>

                {{-- Grid untuk menampung kartu status dan kalender --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kartu Status Pendaftaran --}}
                    <div
                        class="lg:col-span-1 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">

                        @if($pendaftaran)
                            {{-- JIKA USER SUDAH PERNAH MENDAFTAR --}}
                            <h3 class="claude-title text-xl text-white mb-4">Status Pendaftaran Anda</h3>

                            <p class="text-gray-400 mb-2">Nama Pendaftar:</p>
                            <p class="text-white text-lg font-semibold mb-4">{{ $pendaftaran->nama_pendaftar }}</p>

                            <p class="text-gray-400 mb-2">Status Saat Ini:</p>
                            <div>
                                @if($pendaftaran->status == 'pending')
                                    <span class="status-badge bg-yellow-500/20 text-yellow-300">
                                        <i class="fas fa-clock mr-1"></i> Menunggu Review
                                    </span>
                                @elseif($pendaftaran->status == 'approved')
                                    <span class="status-badge bg-green-500/20 text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                @elseif($pendaftaran->status == 'rejected')
                                    <span class="status-badge bg-red-500/20 text-red-300">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @elseif($pendaftaran->status == 'conditional')
                                    <span class="status-badge bg-blue-500/20 text-blue-300">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Diterima Bersyarat
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-400 mt-6">
                                Silakan cek menu "Pendaftaran Magang" di sidebar untuk melihat detail atau riwayat pendaftaran
                                Anda.
                            </p>

                        @else
                            {{-- JIKA USER BELUM PERNAH MENDAFTAR --}}
                            <h3 class="claude-title text-xl text-white mb-4">Anda belum mendaftar magang</h3>
                            <p class="text-gray-400 mb-6">
                                Silakan ajukan pendaftaran magang Anda melalui tombol di bawah ini.
                            </p>
                            <a href="{{ route('daftar.create') }}" class="claude-button">
                                <i class="fas fa-plus mr-2"></i> Daftar Magang Sekarang
                            </a>
                        @endif

                    </div>

                    {{-- (BARU) KARTU UNTUK KALENDER USER --}}
                    <div
                        class="lg:col-span-2 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                        <h3 class="claude-title text-xl text-white mb-4">
                            Kalender Magang BPS Bantul
                        </h3>
                        <div id="calendar" class="claude-calendar"></div>
                    </div>

                </div> {{-- Akhir grid --}}

            </div>
        </div>

    @endif

</x-main-layout>