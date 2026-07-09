<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    // Konstanta status
    const STATUS_DITERIMA   = 'diterima';
    const STATUS_DITINJAU   = 'ditinjau';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SELESAI    = 'selesai';
    const STATUS_DITOLAK    = 'ditolak';

    // Konstanta urgensi
    const URGENCY_NORMAL  = 'normal';
    const URGENCY_PENTING = 'penting';
    const URGENCY_DARURAT = 'darurat';

    // Label untuk tampilan
    const STATUS_LABELS = [
        'diterima'    => 'Diterima',
        'ditinjau'    => 'Ditinjau',
        'in_progress' => 'In Progress',
        'selesai'     => 'Selesai',
        'ditolak'     => 'Ditolak',
    ];

    const URGENCY_LABELS = [
        'normal'  => 'Normal',
        'penting' => 'Penting',
        'darurat' => 'Darurat',
    ];

    // Warna badge per status
    const STATUS_COLORS = [
        'diterima'    => 'bg-blue-100 text-blue-700',
        'ditinjau'    => 'bg-purple-100 text-purple-700',
        'in_progress' => 'bg-yellow-100 text-yellow-700',
        'selesai'     => 'bg-green-100 text-green-700',
        'ditolak'     => 'bg-red-100 text-red-700',
    ];

    // Warna badge per urgensi
    const URGENCY_COLORS = [
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
