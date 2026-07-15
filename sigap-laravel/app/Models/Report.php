<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    // ======================================================
    // Model: Report
    // Merepresentasikan laporan yang dibuat oleh user.
    // Fitur utama:
    // - Soft deletes untuk memulihkan laporan terhapus.
    // - Konstanta status dan urgensi untuk konsistensi business logic.
    // - Relasi: `user()`, `category()`, `statusLogs()`.
    // Catatan: `photo_path` menyimpan path pada disk `public`.
    // ======================================================
    use SoftDeletes;

    // Konstanta status
    public const STATUS_DITERIMA   = 'diterima';
    public const STATUS_DITINJAU   = 'ditinjau';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_SELESAI    = 'selesai';
    public const STATUS_DITOLAK    = 'ditolak';

    // Konstanta urgensi
    public const URGENCY_NORMAL  = 'normal';
    public const URGENCY_PENTING = 'penting';
    public const URGENCY_DARURAT = 'darurat';

    // Label untuk tampilan
    public const STATUS_LABELS = [
    'diterima'    => 'Diterima',
    'ditinjau'    => 'Ditinjau',
    'in_progress' => 'Diproses',
    'selesai'     => 'Selesai',
    'ditolak'     => 'Ditolak',
];

    public const URGENCY_LABELS = [
        'normal'  => 'Normal',
        'penting' => 'Penting',
        'darurat' => 'Darurat',
    ];


    // Warna badge per status
    public const STATUS_COLORS = [
        'diterima'    => 'bg-blue-100 text-blue-700',
        'ditinjau'    => 'bg-purple-100 text-purple-700',
        'in_progress' => 'bg-yellow-100 text-yellow-700',
        'selesai'     => 'bg-green-100 text-green-700',
        'ditolak'     => 'bg-red-100 text-red-700',
    ];

    // Warna badge per urgensi
    public const URGENCY_COLORS = [
        'normal'  => 'bg-gray-100 text-gray-700',
        'penting' => 'bg-orange-100 text-orange-700',
        'darurat' => 'bg-red-100 text-red-700',
    ];

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'photo_path',
        'location_address',
        'latitude',
        'longitude',
        'status',
        'urgency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(ReportStatusLog::class)->orderBy('created_at', 'asc');
    }
}
