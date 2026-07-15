<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // ======================================================
    // Model: Announcement
    // Representasi pengumuman yang dapat dipasang/tampilkan di frontend.
    // Fields: `title`, `content`, `is_pinned`, `created_by`.
    // Casts: `is_pinned` boolean untuk memudahkan pengecekan di UI.
    // Relasi: `creator()` -> belongsTo(User).
    // Digunakan untuk menampilkan pengumuman dan menandai pinned item.
    // ======================================================
    use HasFactory;

    protected $fillable = ['title', 'content', 'is_pinned', 'created_by'];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
