{{-- resources/views/dashboard.blade.php --}}
<x-main-layout>
    <div class="claude-container">
        
        {{-- Header Section dengan Filter Tahun --}}
        <div class="border-b border-[#3a3a3a] header-section">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <h2 class="claude-title text-2xl text-white">
                        {{ __('Dashboard') }}
                    </h2>

                    {{-- FORM FILTER TAHUN --}}
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <label for="year" class="filter-label mb-0">Tampilkan Tahun:</label>
                        <select name="year" id="year" class="filter-select w-40" onchange="this.form.submit()">
                            
                            {{-- ✅ PERUBAHAN LOGIKA DROPDOWN --}}
                            <option value="all" {{ $selectedYear == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($availableYears as $year)
                                @if($year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        
                        {{-- ✅ PERUBAHAN LOGIKA TOMBOL RESET --}}
                        {{-- Hanya tampilkan tombol reset jika yang dipilih BUKAN "Semua Tahun" --}}
                        @if($selectedYear != 'all')
                            <a href="{{ route('dashboard', ['year' => 'all']) }}" class="text-gray-400 hover:text-white text-sm" title="Tampilkan Semua Tahun">
                                <i class="fas fa-times"></i> Tampilkan Semua
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Ini adalah konten utama halaman --}}
        <div class="max-w-7xl mx-auto py-8 px-6">
            
            {{-- Pesan "You're logged in!" --}}
            <div class="bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl overflow-hidden shadow-lg mb-6" style="animation: slideDown 0.5s ease-out;">
                <div class="p-6 text-gray-300">
                    {{ __("You're logged in!") }}
                    
                    {{-- ✅ PERUBAHAN TEKS INFO --}}
                    @if($selectedYear && $selectedYear != 'all')
                        <span class="ml-2 text-gray-400">Menampilkan statistik untuk tahun {{ $selectedYear }}.</span>
                    @else
                        <span class="ml-2 text-gray-400">Menampilkan statistik untuk semua tahun.</span>
                    @endif
                </div>
            </div>

            {{-- KUMPULAN GRAFIK --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                
                <!-- Grafik 1: Pendaftaran per Periode (Bar Chart) -->
                <div class="lg:col-span-3 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                    <h3 class="claude-title text-xl text-white mb-4">
                        Pendaftaran Masuk ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                    </h3>
                    <canvas id="pendaftaranChart"></canvas>
                </div>

                <!-- Grafik 2: Status Pendaftar (Doughnut Chart) -->
                <div class="lg:col-span-2 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                    <h3 class="claude-title text-xl text-white mb-4">
                        Status Pendaftar ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                    </h3>
                    <canvas id="statusChart"></canvas>
                </div>

                <!-- Grafik 3: Magang Mulai per Periode (Line Chart) -->
                <div class="lg:col-span-5 bg-[#2a2a2a]/60 backdrop-blur-md border border-[#3a3a3a] rounded-xl shadow-lg p-6">
                    <h3 class="claude-title text-xl text-white mb-4">
                        Peserta Mulai Magang ({{ $selectedYear != 'all' ? $selectedYear : 'Semua Tahun' }})
                    </h3>
                    <canvas id="magangChart"></canvas>
                </div>

            </div>

        </div>
    </div>

    {{-- SCRIPT UNTUK MEMBUAT GRAFIK --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data dari PHP
            const pendaftaranLabels = @json($pendaftaranChartLabels);
            const pendaftaranData = @json($pendaftaranChartData);
            const magangData = @json($magangChartData);
            const statusData = @json($statusChartData);

            // Opsi global untuk semua chart (agar teks terlihat di dark mode)
            Chart.defaults.color = '#9ca3af';
            Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

            // 1. Grafik Pendaftaran (Bar Chart)
            const ctxPendaftaran = document.getElementById('pendaftaranChart');
            if (ctxPendaftaran) {
                new Chart(ctxPendaftaran, {
                    type: 'bar',
                    data: {
                        labels: pendaftaranLabels,
                        datasets: [{
                            label: 'Jumlah Pendaftar',
                            data: pendaftaranData,
                            backgroundColor: 'rgba(217, 119, 87, 0.6)', // Warna brand
                            borderColor: 'rgba(217, 119, 87, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Pastikan sumbu Y hanya angka bulat
                                }
                            }
                        }
                    }
                });
            }

            // 2. Grafik Status (Doughnut Chart)
            const ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Menunggu', 'Disetujui', 'Ditolak', 'Bersyarat'],
                        datasets: [{
                            label: 'Status Pendaftar',
                            data: statusData,
                            backgroundColor: [
                                'rgba(250, 204, 21, 0.7)',  // Kuning (Pending)
                                'rgba(74, 222, 128, 0.7)', // Hijau (Approved)
                                'rgba(248, 113, 113, 0.7)',// Merah (Rejected)
                                'rgba(96, 165, 250, 0.7)'  // Biru (Conditional)
                            ],
                            borderColor: '#3a3a3a',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom', // Pindahkan legenda ke bawah
                            }
                        }
                    }
                });
            }

            // 3. Grafik Magang (Line Chart)
            const ctxMagang = document.getElementById('magangChart');
            if (ctxMagang) {
                new Chart(ctxMagang, {
                    type: 'line',
                    data: {
                        labels: pendaftaranLabels, // Pakai label yang sama dengan pendaftaran
                        datasets: [{
                            label: 'Peserta Mulai Magang',
                            data: magangData,
                            backgroundColor: 'rgba(96, 165, 250, 0.2)',
                            borderColor: 'rgba(96, 165, 250, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3 // Membuat garis lebih melengkung
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Pastikan sumbu Y hanya angka bulat
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-main-layout>