<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // List semua laporan (admin) dengan search & pagination
    public function index(Request $request)
    {
        $query = Report::with(['user', 'category'])
            ->withTrashed(false);

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('location_address', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $reports    = $query->latest()->paginate(10)->withQueryString();
        $categories = ReportCategory::all();

        return view('admin.reports.index', compact('reports', 'categories'));
    }

    // Detail satu laporan
    public function show(Report $report)
    {
        $report->load(['user', 'category', 'statusLogs.changedBy']);
        return view('admin.reports.show', compact('report'));
    }

    // Form update status
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,ditolak',
            'notes'  => 'nullable|string|max:500',
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $request->status]);

        // Catat di status log
        ReportStatusLog::create([
            'report_id'  => $report->id,
            'changed_by' => Auth::id(),
            'status'     => $request->status,
            'notes'      => $request->notes,
        ]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    // Soft delete laporan
    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
