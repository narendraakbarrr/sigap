<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\ReportStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    // GET /api/v1/reports
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
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'category_id'      => 'required|exists:report_categories,id',
            'location_address' => 'required|string|max:500',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'photo'            => 'nullable|image|max:2048',
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
        ]);

        return new ReportResource($report->load(['user', 'category']));
    }

    // GET /api/v1/reports/{id}
    public function show(Report $report)
    {
        // Warga hanya bisa lihat laporan milik sendiri
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return new ReportResource($report->load(['user', 'category', 'statusLogs.changedBy']));
    }

    // PUT /api/v1/reports/{id}
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
        ]);

        $report->update($request->only([
            'title', 'description', 'category_id', 'location_address'
        ]));

        return new ReportResource($report->load(['user', 'category']));
    }

    // DELETE /api/v1/reports/{id}
    public function destroy(Report $report)
    {
        if (Auth::user()->hasRole('user') && $report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $report->delete();
        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }

    // PUT /api/v1/reports/{id}/status  (admin only)
    public function updateStatus(Request $request, Report $report)
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
