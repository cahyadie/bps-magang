<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magang; // Pastikan Anda memiliki model ini
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil role user yang sedang login
        $role = Auth::user()->role;

        // ===========================================
        //  JIKA USER ADALAH ADMIN
        // ===========================================
        if ($role === 'admin') {

            $selectedYear = $request->input('year', Carbon::now()->year);

            // --- Ambil daftar tahun ---
            $yearsMagang = Pendaftaran::select(DB::raw('YEAR(tanggal_mulai) as year'))->distinct()->pluck('year');
            $yearsPendaftar = Pendaftaran::select(DB::raw('YEAR(created_at) as year'))->distinct()->pluck('year');
            
            $currentYear = Carbon::now()->year;
            $availableYears = $yearsMagang->merge($yearsPendaftar)->push($currentYear)
                                          ->unique()->whereNotNull()->sortDesc();

            // --- Query 1: Data Grafik Status Pendaftar ---
            $pendaftarQuery = Pendaftaran::query();
            
            if ($selectedYear != 'all') {
                $pendaftarQuery->whereYear('created_at', $selectedYear);
            }
            $statusCounts = (clone $pendaftarQuery)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status');

            $totalPending = $statusCounts->get('pending', 0);
            $totalApproved = $statusCounts->get('approved', 0);
            $totalRejected = $statusCounts->get('rejected', 0);
            $totalConditional = $statusCounts->get('conditional', 0);
            
            $statusChartData = [$totalPending, $totalApproved, $totalRejected, $totalConditional];

            // --- Query 2 & 3: Data Pendaftaran & Magang per Periode ---
            $pendaftaranChartLabels = [];
            $pendaftaranChartData = [];
            $magangChartLabels = [];
            $magangChartData = [];

            if ($selectedYear != 'all') {
                // --- TAMPILAN PER BULAN ---
                $pendaftaranChartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $magangChartLabels = $pendaftaranChartLabels;
                
                $pendaftaranPerBulan = array_fill(1, 12, 0);
                $magangPerBulan = array_fill(1, 12, 0);

                $pendaftarData = Pendaftaran::whereYear('created_at', $selectedYear)
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                    ->groupBy('month')
                    ->pluck('count', 'month');
                
                foreach ($pendaftarData as $month => $count) {
                    if (isset($pendaftaranPerBulan[$month])) {
                        $pendaftaranPerBulan[$month] = $count;
                    }
                }
                $pendaftaranChartData = array_values($pendaftaranPerBulan);

                $magangData = Pendaftaran::where('status', 'approved') // Ambil dari pendaftaran yang disetujui
                    ->whereYear('tanggal_mulai', $selectedYear)
                    ->select(DB::raw('MONTH(tanggal_mulai) as month'), DB::raw('COUNT(*) as count'))
                    ->groupBy('month')
                    ->pluck('count', 'month');

                foreach ($magangData as $month => $count) {
                     if (isset($magangPerBulan[$month])) {
                        $magangPerBulan[$month] = $count;
                    }
                }
                $magangChartData = array_values($magangPerBulan);

            } else {
                // --- TAMPILAN PER TAHUN ---
                $allYears = $availableYears->sort(); 
                $pendaftaranChartLabels = $allYears->toArray();
                $magangChartLabels = $allYears->toArray();

                $pendaftaranPerTahun = Pendaftaran::select(DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(*) as count'))
                    ->groupBy('year')
                    ->pluck('count', 'year');
                
                $magangPerTahun = Pendaftaran::where('status', 'approved')
                    ->select(DB::raw('YEAR(tanggal_mulai) as year'), DB::raw('COUNT(*) as count'))
                    ->groupBy('year')
                    ->pluck('count', 'year');

                foreach ($allYears as $year) {
                    $pendaftaranChartData[] = $pendaftaranPerTahun->get($year, 0);
                    $magangChartData[] = $magangPerTahun->get($year, 0);
                }
            }

            // ======================================================
            // LOGIKA KALENDER UNTUK ADMIN (IKON)
            // ======================================================
            
            $queryKalender = Pendaftaran::where('status', 'approved')
                             ->whereNotNull(['tanggal_mulai', 'tanggal_selesai']); // Pastikan tanggal ada
                
            if ($selectedYear != 'all') {
                $queryKalender->where(function ($query) use ($selectedYear) {
                    $query->whereYear('tanggal_mulai', $selectedYear)
                          ->orWhereYear('tanggal_selesai', $selectedYear);
                });
            }

            // Gunakan flatMap untuk membuat DUA event (mulai & selesai) per pendaftaran
            $calendarEvents = $queryKalender->get()->flatMap(function ($pendaftaran) {
                return [
                    // Event 1: Tanggal Mulai
                    [
                        'title' => $pendaftaran->nama_pendaftar . ' (Mulai)',
                        'start' => $pendaftaran->tanggal_mulai, // Hanya tanggal mulai
                        'display' => 'list-item', // Tampilkan sebagai "dot" (yang akan kita styling)
                        'extendedProps' => ['icon' => 'play'] // Properti kustom untuk ikon
                    ],
                    // Event 2: Tanggal Selesai
                    [
                        'title' => $pendaftaran->nama_pendaftar . ' (Selesai)',
                        'start' => $pendaftaran->tanggal_selesai, // Hanya tanggal selesai
                        'display' => 'list-item',
                        'extendedProps' => ['icon' => 'flag'] // Properti kustom untuk ikon
                    ]
                ];
            });


            // Kembalikan view dashboard admin
            return view('dashboard', [
                'role' => 'admin',
                'statusChartData' => $statusChartData,
                'pendaftaranChartLabels' => $pendaftaranChartLabels,
                'pendaftaranChartData' => $pendaftaranChartData,
                'magangChartLabels' => $magangChartLabels,
                'magangChartData' => $magangChartData,
                'availableYears' => $availableYears,
                'selectedYear' => $selectedYear,
                'calendarEvents' => $calendarEvents,
            ]);

        // ===========================================
        //  JIKA USER ADALAH USER BIASA
        // ===========================================
        } elseif ($role === 'user') {
            
            $pendaftaran = Pendaftaran::where('user_id', Auth::id())
                                          ->latest() 
                                          ->first(); 

            // ======================================================
            // ✅ PERUBAHAN DI SINI: LOGIKA KALENDER UNTUK USER
            // ======================================================
            
            // Ambil SEMUA pendaftaran yang disetujui (BUKAN HANYA user_id yang login)
            $semuaPendaftaran = Pendaftaran::where('status', 'approved')
                                        ->whereNotNull(['tanggal_mulai', 'tanggal_selesai'])
                                        ->get();

            // Gunakan flatMap untuk membuat DUA event (mulai & selesai)
            $calendarEvents = $semuaPendaftaran->flatMap(function ($p) {
                return [
                    // Event 1: Tanggal Mulai
                    [
                        // Tampilkan nama pendaftar, bukan "Magang Anda"
                        'title' => $p->nama_pendaftar . ' (Mulai)', 
                        'start' => $p->tanggal_mulai,
                        'display' => 'list-item',
                        'extendedProps' => ['icon' => 'play']
                    ],
                    // Event 2: Tanggal Selesai
                    [
                        // Tampilkan nama pendaftar, bukan "Magang Anda"
                        'title' => $p->nama_pendaftar . ' (Selesai)',
                        'start' => $p->tanggal_selesai,
                        'display' => 'list-item',
                        'extendedProps' => ['icon' => 'flag']
                    ]
                ];
            });
            // --- AKHIR PERUBAHAN ---


            // Kembalikan view dashboard user
            return view('dashboard', [
                'role' => 'user', 
                'pendaftaran' => $pendaftaran, 
                'calendarEvents' => $calendarEvents, // Kirim data kalender yang sudah berisi SEMUA pendaftar
            ]);
        }

        // Fallback jika ada role aneh
        Auth::logout();
        return redirect()->route('login');
    }
}