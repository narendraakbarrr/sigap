<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    private function adminDashboard()
    {
        // Statistik utama
        $stats = [
            'total'       => Report::count(),
            'diterima'    => Report::where('status', 'diterima')->count(),
            'ditinjau'    => Report::where('status', 'ditinjau')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'selesai'     => Report::where('status', 'selesai')->count(),
            'ditolak'     => Report::where('status', 'ditolak')->count(),
            'darurat'     => Report::where('urgency', 'darurat')->count(),
            'total_user'  => User::role('user')->count(),
        ];

        // Laporan terbaru
        $laporanTerbaru = Report::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Laporan darurat yang belum selesai
        $laporanDarurat = Report::with(['user', 'category'])
            ->where('urgency', 'darurat')
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->latest()
            ->take(5)
            ->get();

        // Data grafik — laporan per kategori
        $categoryStats = ReportCategory::withCount('reports')
            ->orderByDesc('reports_count')
            ->get();
        $topCategories = $categoryStats->take(5);
        $otherCategories = $categoryStats->slice(5);

        // also provide the older `perKategori` shape for views that still expect it
        $perKategori = $categoryStats->map(fn($c) => [
            'name'  => $c->name,
            'count' => $c->reports_count,
        ]);

        // Data grafik — laporan per hari (7 hari terakhir)
        $perHari = Report::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(fn($r) => [
                'tanggal' => $r->tanggal,
                'total'   => $r->total,
            ]);

        return view('admin.dashboard', compact(
            'stats',
            'laporanTerbaru',
            'laporanDarurat',
            'perKategori',
            'perHari',
            'topCategories',
            'otherCategories'
        ));
    }

    private function userDashboard($user)
    {
        $myReports = Report::where('user_id', $user->id);

        $stats = [
            'total'       => $myReports->count(),
            'diterima'    => (clone $myReports)->where('status', 'diterima')->count(),
            'ditinjau'    => (clone $myReports)->where('status', 'ditinjau')->count(),
            'in_progress' => (clone $myReports)->where('status', 'in_progress')->count(),
            'selesai'     => (clone $myReports)->where('status', 'selesai')->count(),
            'ditolak'     => (clone $myReports)->where('status', 'ditolak')->count(),
        ];

        $laporanTerbaru = Report::with(['category'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $categoryStats = ReportCategory::withCount('reports')->orderByDesc('reports_count')->get();
        $topCategories = $categoryStats->take(5);
        $otherCategories = $categoryStats->slice(5);

        $announcements = Announcement::query()
            ->orderByDesc('is_pinned')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'laporanTerbaru', 'user', 'topCategories', 'otherCategories', 'announcements'));
    }
}
