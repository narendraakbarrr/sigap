<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebReportStoreRequest;
use App\Http\Requests\WebReportUpdateRequest;
use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\ReportStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // ======================================================
    // ReportController (Web)
    // Menangani tampilan dan aksi terkait laporan pada panel admin
    // dan daftar laporan untuk user. Fitur meliputi: pencarian,
    // filter, pagination, pengelolaan status, soft delete, restore,
    // dan penghapusan permanen.
    // ======================================================
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

    // Daftar laporan milik user (halaman "Laporan Saya")
    /// Menyediakan listing terbatas pada laporan milik user terautentikasi.
    public function userIndex(Request $request)
    {
        $userId = auth()->id();

        $query = Report::with(['category'])
            ->where('user_id', $userId)
            ->withTrashed(false);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'       => Report::where('user_id', $userId)->count(),
            'diterima'    => Report::where('user_id', $userId)->where('status', 'diterima')->count(),
            'ditinjau'    => Report::where('user_id', $userId)->where('status', 'ditinjau')->count(),
            'in_progress' => Report::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'selesai'     => Report::where('user_id', $userId)->where('status', 'selesai')->count(),
            'ditolak'     => Report::where('user_id', $userId)->where('status', 'ditolak')->count(),
        ];

        $categories = ReportCategory::all();

        return view('user.reports.index', compact('reports', 'stats', 'categories'));
    }

    // Halaman daftar laporan untuk warga
    public function create()
    {
        $categories = ReportCategory::all();

        return view('user.reports.create', compact('categories'));
    }

    // Simpan laporan baru oleh warga
    public function store(WebReportStoreRequest $request)
    {
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'photo_path' => $photoPath,
            'location_address' => $request->location_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => Report::STATUS_DITERIMA,
            'urgency' => $request->urgency ?? Report::URGENCY_NORMAL,
        ]);

        return redirect()
            ->route('user.reports.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    // Detail satu laporan
    /// Menampilkan halaman detail laporan termasuk histori status.
    public function show(Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            abort(403);
        }

        $report->load(['user', 'category', 'statusLogs.changedBy']);

        if (Auth::user()->hasRole('user')) {
            return view('user.reports.show', compact('report'));
        }

        return view('admin.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            abort(403);
        }

        if (Auth::user()->hasRole('user') && $report->status !== Report::STATUS_DITERIMA) {
            return redirect()
                ->route('user.reports.show', $report)
                ->with('error', 'Laporan hanya dapat diedit selama berstatus Diterima.');
        }

        $categories = ReportCategory::all();

        return view('user.reports.edit', compact('report', 'categories'));
    }

    public function update(WebReportUpdateRequest $request, Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            abort(403);
        }

        if (Auth::user()->hasRole('user') && $report->status !== Report::STATUS_DITERIMA) {
            return redirect()
                ->route('user.reports.show', $report)
                ->with('error', 'Laporan hanya dapat diedit selama berstatus Diterima.');
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location_address' => $request->location_address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'urgency' => $request->urgency ?? Report::URGENCY_NORMAL,
        ];

        if ($request->hasFile('photo')) {
            if ($report->photo_path) {
                Storage::disk('public')->delete($report->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('reports', 'public');
        }

        $report->update($data);

        return redirect()
            ->route('user.reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui');
    }

    // Form update status
    /// Memproses pembaruan status laporan oleh admin dan membuat log status.
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status'           => 'required|in:diterima,ditinjau,in_progress,selesai,ditolak',
            'urgency'          => 'nullable|in:normal,penting,darurat',
            'notes'            => 'nullable|string|max:500',
            'task_description' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status'  => $request->status,
            'urgency' => $request->urgency ?? $report->urgency,
        ]);

        ReportStatusLog::create([
            'report_id'        => $report->id,
            'changed_by'       => Auth::id(),
            'status'           => $request->status,
            'notes'            => $request->notes,
            'task_description' => $request->task_description,
        ]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    // Soft delete laporan
    /// Melakukan soft-delete untuk laporan (dapat dipulihkan).
    public function destroy(Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            abort(403);
        }

        if ($report->photo_path) {
            Storage::disk('public')->delete($report->photo_path);
        }

        $report->delete();

        return redirect()
            ->route(Auth::user()->hasRole('user') ? 'user.reports.index' : 'admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    // Halaman laporan yang sudah dihapus (trash)
    /// Menampilkan daftar laporan yang telah di-soft-delete.
    public function trash()
    {
        $reports = Report::onlyTrashed()
            ->with(['user', 'category'])
            ->latest('deleted_at')
            ->paginate(10);
        return view('admin.reports.trash', compact('reports'));
    }

    // Restore laporan dari trash
    /// Mengembalikan laporan yang telah dihapus (soft delete restore).
    public function restore($id)
    {
        $report = Report::onlyTrashed()->findOrFail($id);
        $report->restore();
        return redirect()
            ->route('admin.reports.trash')
            ->with('success', 'Laporan berhasil dipulihkan.');
    }

    // Hapus permanen
    /// Menghapus record laporan secara permanen dari database.
    public function forceDelete($id)
    {
        $report = Report::onlyTrashed()->findOrFail($id);
        $report->forceDelete();
        return redirect()
            ->route('admin.reports.trash')
            ->with('success', 'Laporan berhasil dihapus permanen.');
    }
}
