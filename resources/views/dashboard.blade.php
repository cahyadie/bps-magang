<x-app-layout>

    {{-- BAGIAN 1: STYLES --}}
    @push('styles')
    <style>
        .status-badge {
            display: inline-flex; align-items: center; padding: 0.5rem 1rem;
            border-radius: 9999px; font-weight: 600; font-size: 0.875rem;
        }
        .claude-button {
            background: linear-gradient(135deg, #d97757 0%, #e88968 100%);
            color: #ffffff; padding: 0.75rem 1.5rem; border-radius: 12px;
            font-weight: 600; transition: all 0.3s; border: none; cursor: pointer;
            display: inline-flex; align-items: center; text-decoration: none;
            box-shadow: 0 4px 15px rgba(217, 119, 87, 0.3);
        }
        .claude-button:hover {
            background: linear-gradient(135deg, #e88968 0%, #f09a7a 100%);
            transform: translateY(-2px);
        }
        /* FullCalendar Customization */
        .claude-calendar { color: #d1d5db; }
        .fc .fc-toolbar-title { color: #ffffff; font-size: 1.25rem !important; }
        .fc .fc-button { background-color: #374151; border-color: #4b5563; color: #d1d5db; }
        .fc-theme-standard .fc-scrollgrid, .fc-theme-standard th, .fc-theme-standard td { border-color: #3a3a3a; }
        .fc-event { cursor: help; }
        .fc-daygrid-event { background-color: transparent !important; border: none !important; margin-top: 4px; display: inline-flex; }
        .fc-daygrid-event-dot, .fc-event-title, .fc-event-time { display: none !important; }
        .fc .fc-daygrid-day-number { color: #d1d5db !important; }

        @if($role == 'admin')
            .fc .fc-button-primary { background-color: #d97757; border-color: #d97757; }
            .fc .fc-daygrid-day.fc-day-today { background-color: rgba(217, 119, 87, 0.1); }
            .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { color: #22c55e !important; font-weight: bold; }
        @else
            .fc .fc-button-primary { background-color: #22c55e; border-color: #22c55e; }
            .fc .fc-daygrid-day.fc-day-today { background-color: rgba(34, 197, 94, 0.1); }
            .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { color: #e88968 !important; font-weight: bold; }
        @endif
    </style>
    @endpush

    {{-- BAGIAN 2: SCRIPTS --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 2.1 CALENDAR
            const calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                const events = @json($calendarEvents);
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    headerToolbar: { left: 'prev,next', center: 'title', right: 'dayGridMonth' }, // Simplified toolbar
                    events: events,
                    height: 'auto',
                    eventContent: function (info) {
                        const icon = info.event.extendedProps.icon;
                        let iconClass = icon === 'play' ? 'fas fa-user text-green-400' : (icon === 'flag' ? 'fas fa-user-check text-red-400' : '');
                        if (iconClass) {
                            let el = document.createElement('i');
                            el.className = `${iconClass} text-sm md:text-lg`;
                            el.setAttribute('title', info.event.title);
                            return { domNodes: [el] };
                        }
                    }
                });
                calendar.render();
            }

            // 2.2 CHARTS (ADMIN ONLY)
            @if($role == 'admin')
                const labels = @json($pendaftaranChartLabels);
                Chart.defaults.color = '#9ca3af';
                Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

                const createChart = (id, type, data, options = {}) => {
                    const ctx = document.getElementById(id);
                    if (ctx) new Chart(ctx, { type, data, options });
                };

                // Chart Pendaftaran (Bar)
                createChart('pendaftaranChart', 'bar', {
                    labels: labels,
                    datasets: [{
                        label: 'Pendaftar',
                        data: @json($pendaftaranChartData),
                        backgroundColor: 'rgba(217, 119, 87, 0.6)',
                        borderColor: 'rgba(217, 119, 87, 1)',
                        borderWidth: 1
                    }]
                }, { responsive: true, plugins: { legend: { display: false } } });

                // Chart Status (Doughnut)
                createChart('statusChart', 'doughnut', {
                    labels: ['Menunggu', 'Disetujui', 'Ditolak', 'Bersyarat'],
                    datasets: [{
                        data: @json($statusChartData),
                        backgroundColor: ['rgba(250, 204, 21, 0.7)', 'rgba(74, 222, 128, 0.7)', 'rgba(248, 113, 113, 0.7)', 'rgba(96, 165, 250, 0.7)'],
                        borderColor: '#3a3a3a',
                        borderWidth: 2
                    }]
                }, { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } });
            @endif
        });
    </script>
    @endpush

    {{-- BAGIAN 3: VIEW LAYOUT --}}
    <div class="claude-container">
        
        {{-- 3.1 Header --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <h2 class="claude-title text-xl sm:text-2xl text-white">
                        {{ $role == 'admin' ? __('Dashboard Admin') : __('Dashboard') }}
                    </h2>

                    @if($role == 'admin')
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <label for="year" class="filter-label mb-0 text-gray-300 text-sm">Tahun:</label>
                            <select name="year" id="year" class="filter-select w-32 bg-[#1a1a1a] text-white border-[#3a3a3a] rounded-lg text-sm" onchange="this.form.submit()">
                                <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua</option>
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3.2 Content --}}
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6">
            
            {{-- Welcome --}}
            <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl p-6 shadow-lg mb-6 flex justify-between items-center">
                <div>
                    <p class="text-gray-300">
                        {{ $role == 'admin' ? "Halo Admin," : "Selamat datang," }}
                        <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            @if($role == 'admin')
                {{-- LAYOUT ADMIN --}}
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    
                    {{-- Statistik Baris Atas --}}
                    <div class="lg:col-span-3 bg-[#2a2a2a]/60 border border-[#3a3a3a] rounded-xl p-6 shadow-lg">
                        <h3 class="text-white mb-4 font-medium flex items-center gap-2">
                            <i class="fas fa-chart-bar text-[#d97757]"></i> Pendaftaran Masuk
                        </h3>
                        <div class="h-64"><canvas id="pendaftaranChart"></canvas></div>
                    </div>
                    <div class="lg:col-span-2 bg-[#2a2a2a]/60 border border-[#3a3a3a] rounded-xl p-6 shadow-lg">
                        <h3 class="text-white mb-4 font-medium flex items-center gap-2">
                            <i class="fas fa-chart-pie text-blue-400"></i> Status Pendaftar
                        </h3>
                        <div class="h-64 flex justify-center"><canvas id="statusChart"></canvas></div>
                    </div>

                    {{-- Peserta Magang Aktif (CARDS) --}}
                    <div class="lg:col-span-5">
                        <h3 class="text-white mb-4 text-lg font-semibold flex items-center gap-2">
                            <i class="fas fa-users text-green-400"></i> Sedang Magang Hari Ini
                        </h3>
                        
                        @if($activeInterns->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($activeInterns as $intern)
                                    <div class="bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-4 flex items-center gap-4 hover:border-[#d97757]/50 transition-colors">
                                        {{-- Avatar --}}
                                        <div class="flex-shrink-0">
                                            @if($intern->foto)
                                                <img src="{{ asset('storage/' . $intern->foto) }}" alt="{{ $intern->nama }}" class="w-12 h-12 rounded-full object-cover border border-[#3a3a3a]">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-[#d97757]/20 text-[#d97757] flex items-center justify-center font-bold text-lg border border-[#d97757]/30">
                                                    {{ substr($intern->nama, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Info --}}
                                        <div class="overflow-hidden">
                                            <h4 class="text-white font-medium truncate">{{ $intern->nama }}</h4>
                                            <p class="text-xs text-gray-400 truncate">{{ $intern->asal_kampus }}</p>
                                            <div class="mt-1 flex items-center gap-1 text-[10px] text-green-400 bg-green-900/20 px-2 py-0.5 rounded-full w-fit">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                                Aktif s.d {{ \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-[#2a2a2a]/40 border border-[#3a3a3a] border-dashed rounded-xl p-8 text-center text-gray-500">
                                <i class="fas fa-user-slash text-2xl mb-2"></i>
                                <p>Tidak ada peserta magang yang aktif hari ini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Kalender --}}
                    <div class="lg:col-span-5 bg-[#2a2a2a]/60 border border-[#3a3a3a] rounded-xl p-6 shadow-lg">
                        <h3 class="text-white mb-4 font-medium flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-yellow-400"></i> Kalender Operasional
                        </h3>
                        <div id="calendar" class="claude-calendar"></div>
                    </div>
                </div>

            @else
                {{-- LAYOUT USER --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Status Pendaftaran --}}
                    <div class="lg:col-span-1 bg-[#2a2a2a]/60 border border-[#3a3a3a] rounded-xl p-6 shadow-lg h-fit">
                        <h3 class="text-white mb-4 font-bold border-b border-[#3a3a3a] pb-2">Status Saya</h3>
                        @if($pendaftaran)
                            <div class="text-center mb-6">
                                @if($pendaftaran->pas_foto)
                                    <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}" class="w-20 h-20 rounded-full mx-auto mb-3 object-cover border-2 border-[#3a3a3a]">
                                @else
                                    <div class="w-20 h-20 rounded-full bg-[#3a3a3a] mx-auto mb-3 flex items-center justify-center">
                                        <i class="fas fa-user text-3xl text-gray-500"></i>
                                    </div>
                                @endif
                                <h4 class="text-white font-semibold text-lg">{{ $pendaftaran->nama_pendaftar }}</h4>
                                <p class="text-sm text-gray-400">{{ $pendaftaran->asal_kampus }}</p>
                            </div>
                            
                            @php
                                $statusMap = [
                                    'pending' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-300', 'label' => 'Menunggu Review', 'icon' => 'fa-clock'],
                                    'approved' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-300', 'label' => 'Disetujui', 'icon' => 'fa-check-circle'],
                                    'rejected' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-300', 'label' => 'Ditolak', 'icon' => 'fa-times-circle'],
                                    'conditional' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-300', 'label' => 'Bersyarat', 'icon' => 'fa-info-circle']
                                ];
                                $s = $statusMap[$pendaftaran->status];
                            @endphp

                            <div class="{{ $s['bg'] }} {{ $s['text'] }} p-3 rounded-lg flex items-center gap-3 mb-4">
                                <i class="fas {{ $s['icon'] }} text-xl"></i>
                                <div>
                                    <p class="text-xs opacity-75">Status Saat Ini</p>
                                    <p class="font-bold">{{ $s['label'] }}</p>
                                </div>
                            </div>

                            @if($pendaftaran->status == 'approved' && !$pendaftaran->konfirmasi_at)
                                <a href="{{ route('daftar.konfirmasi', $pendaftaran) }}" class="claude-button w-full justify-center text-sm">
                                    Konfirmasi Kehadiran
                                </a>
                            @endif
                        @else
                            <div class="text-center py-6">
                                <p class="text-gray-400 mb-4">Anda belum mendaftar.</p>
                                <a href="{{ route('daftar.create') }}" class="claude-button w-full justify-center">
                                    <i class="fas fa-plus mr-2"></i> Daftar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan (Kalender & Peserta Lain) --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Kalender --}}
                        <div class="bg-[#2a2a2a]/60 border border-[#3a3a3a] rounded-xl p-6 shadow-lg">
                            <h3 class="text-white mb-4 font-bold flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-yellow-400"></i> Kalender Magang
                            </h3>
                            <div id="calendar" class="claude-calendar"></div>
                        </div>

                        {{-- Peserta Lain (Card Grid untuk User) --}}
                        <div>
                            <h3 class="text-white mb-4 text-lg font-semibold flex items-center gap-2">
                                <i class="fas fa-user-friends text-blue-400"></i> Rekan Magang Aktif
                            </h3>
                            @if($activeInterns->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($activeInterns as $intern)
                                        <div class="bg-[#2a2a2a] border border-[#3a3a3a] rounded-xl p-4 flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                @if($intern->foto)
                                                    <img src="{{ asset('storage/' . $intern->foto) }}" class="w-10 h-10 rounded-full object-cover border border-[#3a3a3a]">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold">
                                                        {{ substr($intern->nama, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <h4 class="text-gray-200 font-medium text-sm truncate">{{ $intern->nama }}</h4>
                                                <p class="text-xs text-gray-500 truncate">{{ $intern->asal_kampus }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-gray-500 text-sm border border-[#3a3a3a] border-dashed rounded-lg">
                                    Belum ada rekan magang lain yang aktif hari ini.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>