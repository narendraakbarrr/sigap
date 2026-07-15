<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\ReportStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportStatusRequest;
use App\Http\Requests\UpdateReportRequest;

class ReportController extends Controller
{
    // ======================================================
    // ReportController (API)
    // Menangani operasi CRUD untuk entitas laporan yang dipakai
    // oleh frontend mobile/web. Metode di kelas ini bertanggung jawab
    // untuk: pencarian/paginasi laporan, pembuatan laporan baru
    // termasuk upload foto, pengambilan detail, pembaruan jika
    // laporan masih berstatus 'diterima', penghapusan, dan
    // pembaruan status oleh admin disertai pencatatan pada log status.
    // Keamanan: beberapa operasi membatasi akses berdasarkan role
    // (mis. warga hanya melihat/kelola laporan mereka sendiri).
    // ======================================================

    // GET /api/v1/reports
    /// Mengembalikan daftar laporan dengan relasi `user` dan `category`.
    /// - Jika user berrole `user`, hanya mengembalikan laporan milik user tersebut.
    /// - Response: koleksi `ReportResource` berpaginasi (10 per halaman).
    public function index(Request $request)
    {
        $query = Report::with(['user', 'category']);

        // Warga hanya lihat laporan milik sendiri
        if (Auth::user()->hasRole('user')) {
            $query->where('user_id', Auth::id());
        }

        $reports = $query->latest()->paginate(10);
        return ReportResource::collection($reports);
    }

    // POST /api/v1/reports
    /// Membuat laporan baru.
    /// - Validasi: title, description, category_id, location_address; photo opsional.
    /// - Jika ada file `photo`, disimpan di disk `public` pada folder `reports`.
    /// - Menetapkan `status` awal ke `diterima` dan `urgency` default `normal`.
    /// - Return: `ReportResource` dari model yang baru dibuat.
    public function store(StoreReportRequest $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'category_id'      => 'required|exists:report_categories,id',
            'location_address' => 'required|string|max:500',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'photo'            => 'nullable|image|max:2048',
            'urgency'          => 'nullable|in:normal,penting,darurat',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                                 ->store('reports', 'public');
        }

        $report = Report::create([
            'user_id'          => Auth::id(),
            'category_id'      => $request->category_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'photo_path'       => $photoPath,
            'location_address' => $request->location_address,
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'status'           => 'diterima',
            'urgency'          => $request->urgency ?? 'normal',
        ]);

        return new ReportResource($report->load(['user', 'category']));
    }

    // GET /api/v1/reports/{id}
    /// Mengembalikan detail laporan termasuk relasi `user`, `category`,
    /// dan `statusLogs.changedBy`. Jika peminta berrole `user`, pastikan
    /// hanya dapat mengakses laporan miliknya sendiri.
    public function show(Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return new ReportResource($report->load(['user', 'category', 'statusLogs.changedBy']));
    }

    // PUT /api/v1/reports/{id}
    /// Memperbarui laporan jika pemilik yang mengirim dan status masih `diterima`.
    /// - Validasi: field tertentu bersifat `sometimes`.
    /// - Return: resource yang diperbarui.
    public function update(Request $request, Report $report)
    {
        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        if ($report->status !== 'diterima') {
            return response()->json([
                'message' => 'Laporan yang sudah diproses tidak dapat diedit'
            ], 422);
        }

        $request->validate([
            'title'            => 'sometimes|string|max:255',
            'description'      => 'sometimes|string',
            'category_id'      => 'sometimes|exists:report_categories,id',
            'location_address' => 'sometimes|string|max:500',
            'urgency'          => 'sometimes|in:normal,penting,darurat',
        ]);

        $report->update($request->only([
            'title', 'description', 'category_id', 'location_address', 'urgency'
        ]));

        return new ReportResource($report->load(['user', 'category']));
    }

    // DELETE /api/v1/reports/{id}
    /// Menghapus laporan. Warga hanya dapat menghapus miliknya sendiri.
    public function destroy(Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $report->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }

    // PUT /api/v1/reports/{id}/status  (admin only)
    /// Memperbarui status laporan oleh admin dan mencatat perubahan ke log status.
    /// - Validasi: status harus salah satu dari daftar yang diperbolehkan.
    /// - Side-effect: membuat record `ReportStatusLog` untuk audit trail.
    public function updateStatus(UpdateReportStatusRequest $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,ditolak',
            'notes'  => 'nullable|string|max:500',
        ]);

        $report->update(['status' => $request->status]);

        ReportStatusLog::create([
            'report_id'  => $report->id,
            'changed_by' => Auth::id(),
            'status'     => $request->status,
            'notes'      => $request->notes,
        ]);

        return new ReportResource($report->load(['user', 'category']));
    }
}
