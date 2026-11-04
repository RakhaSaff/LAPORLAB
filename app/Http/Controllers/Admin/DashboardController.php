<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Laporan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalLaporan    = Laporan::count();
        $perluVerifikasi = Laporan::where('status', 'menunggu')->count();
        $sudahVerifikasi = Laporan::whereIn('status', ['pending', 'diproses', 'selesai'])->count();
        $selesai         = Laporan::where('status', 'selesai')->count();

        // Grafik laporan per bulan
        $tahun = request('tahun', Carbon::now()->year);
        $chartVerifikasi      = [];
        $chartBelumVerifikasi = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartVerifikasi[] = Laporan::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->whereIn('status', ['pending', 'diproses', 'selesai'])
                ->count();

            $chartBelumVerifikasi[] = Laporan::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->where('status', 'menunggu')
                ->count();
        }

        // Dominan kerusakan berdasarkan nama barang (top 5)
        $dominan = Laporan::selectRaw('nama_barang, count(*) as total')
            ->groupBy('nama_barang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $dominanLabels = $dominan->pluck('nama_barang')->toArray();
        $dominanData   = $dominan->pluck('total')->toArray();

        // Laporan terbaru (5 data)
        $laporanTerbaru = Laporan::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalLaporan',
            'perluVerifikasi',
            'sudahVerifikasi',
            'selesai',
            'chartVerifikasi',
            'chartBelumVerifikasi',
            'dominanLabels',
            'dominanData',
            'laporanTerbaru',
            'tahun'
        ));
    }
}