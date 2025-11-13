<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magang;
use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ✅ PERUBAHAN DI SINI
        // Ambil 'year' dari request. Jika tidak ada, default-nya adalah TAHUN INI.
        $selectedYear = $request->input('year', Carbon::now()->year);

        // --- Ambil daftar tahun yang tersedia untuk filter dropdown ---
        $yearsMagang = Magang::select(DB::raw('YEAR(tanggal_mulai) as year'))->distinct()->pluck('year');
        $yearsPendaftar = Pendaftaran::select(DB::raw('YEAR(created_at) as year'))->distinct()->pluck('year');
        
        // Pastikan tahun ini ada di daftar, bahkan jika belum ada data
        $currentYear = Carbon::now()->year;
        $availableYears = $yearsMagang->merge($yearsPendaftar)->push($currentYear)
                                      ->unique()->sortDesc();

        // --- Query 1: Data Grafik Status Pendaftar (Doughnut Chart) ---
        $pendaftarQuery = Pendaftaran::query();
        
        // ✅ PERUBAHAN LOGIKA FILTER
        // Jika $selectedYear BUKAN 'all', terapkan filter
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


        // --- Query 2 & 3: Data Pendaftaran & Magang per Periode (Bar & Line Chart) ---
        
        $pendaftaranChartLabels = [];
        $pendaftaranChartData = [];
        $magangChartLabels = [];
        $magangChartData = [];

        // ✅ PERUBAHAN LOGIKA FILTER
        if ($selectedYear != 'all') {
            // --- TAMPILAN PER BULAN (jika tahun dipilih) ---
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
            // --- TAMPILAN PER TAHUN (jika "Semua Tahun" dipilih) ---
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

        return view('dashboard', [
            'statusChartData' => $statusChartData,
            'pendaftaranChartLabels' => $pendaftaranChartLabels,
            'pendaftaranChartData' => $pendaftaranChartData,
            'magangChartLabels' => $magangChartLabels,
            'magangChartData' => $magangChartData,
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
        ]);
    }
}