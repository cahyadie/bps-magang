<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magang;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI

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
            // Jalankan SEMUA logika dashboard admin Anda yang sudah ada

            $selectedYear = $request->input('year', Carbon::now()->year);

            // --- Ambil daftar tahun ---
            $yearsMagang = Magang::select(DB::raw('YEAR(tanggal_mulai) as year'))->distinct()->pluck('year');
            $yearsPendaftar = Pendaftaran::select(DB::raw('YEAR(created_at) as year'))->distinct()->pluck('year');
            
            $currentYear = Carbon::now()->year;
            $availableYears = $yearsMagang->merge($yearsPendaftar)->push($currentYear)
                                        ->unique()->sortDesc();

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
                    $pendaftaranPerBulan[$month] = $count;
                }
                $pendaftaranChartData = array_values($pendaftaranPerBulan);

                $magangData = Magang::whereYear('tanggal_mulai', $selectedYear)
                    ->select(DB::raw('MONTH(tanggal_mulai) as month'), DB::raw('COUNT(*) as count'))
                    ->groupBy('month')
                    ->pluck('count', 'month');

                foreach ($magangData as $month => $count) {
                    $magangPerBulan[$month] = $count;
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
                
                $magangPerTahun = Magang::select(DB::raw('YEAR(tanggal_mulai) as year'), DB::raw('COUNT(*) as count'))
                    ->groupBy('year')
                    ->pluck('count', 'year');

                foreach ($allYears as $year) {
                    $pendaftaranChartData[] = $pendaftaranPerTahun->get($year, 0);
                    $magangChartData[] = $magangPerTahun->get($year, 0);
                }
            }

            // Kembalikan view dashboard admin dengan semua data chart
            return view('dashboard', [
                'role' => 'admin', // <-- KIRIM ROLE KE VIEW
                'statusChartData' => $statusChartData,
                'pendaftaranChartLabels' => $pendaftaranChartLabels,
                'pendaftaranChartData' => $pendaftaranChartData,
                'magangChartLabels' => $magangChartLabels,
                'magangChartData' => $magangChartData,
                'availableYears' => $availableYears,
                'selectedYear' => $selectedYear,
            ]);

        // ===========================================
        //  JIKA USER ADALAH USER BIASA
        // ===========================================
        } elseif ($role === 'user') {
            
            // Ambil data pendaftaran terakhir milik user ini
            // (Asumsi: ada kolom 'user_id' di tabel Pendaftaran)
            $pendaftaran = Pendaftaran::where('user_id', Auth::id())
                                      ->latest() // Ambil yang paling baru
                                      ->first(); 

            // Kembalikan view dashboard user
            return view('dashboard', [
                'role' => 'user', // <-- KIRIM ROLE KE VIEW
                'pendaftaran' => $pendaftaran, // Kirim data pendaftarannya
            ]);
        }

        // Fallback jika ada role aneh (seharusnya tidak terjadi)
        Auth::logout();
        return redirect()->route('login');
    }
}