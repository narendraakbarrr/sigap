<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportStatusLog extends Model
{
    // ======================================================
    // Model: ReportStatusLog
    // Menyimpan riwayat perubahan status untuk sebuah laporan.
    // Fields: `report_id`, `changed_by` (user id yang mengubah), `status`, `notes`, `task_description`.
    // Relasi: `report()` -> belongsTo(Report), `changedBy()` -> belongsTo(User).
    // Digunakan untuk audit trail dan tampilan histori status di UI.
    // ======================================================
    protected $fillable = [
        'report_id',
        'changed_by',
        'status',
        'notes',
        'task_description',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
