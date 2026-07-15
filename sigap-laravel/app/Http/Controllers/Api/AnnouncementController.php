<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    // ======================================================
    // AnnouncementController (API)
    // Menyediakan daftar pengumuman untuk ditampilkan pada
    // frontend. Pengumuman yang bertanda `is_pinned` akan ditempatkan
    // di atas daftar.
    // ======================================================
    /// GET /api/v1/announcements
    /// - Mengembalikan koleksi `AnnouncementResource` yang sudah diurutkan.
    public function index()
    {
        $announcements = Announcement::orderByDesc('is_pinned')->latest()->get();

        return AnnouncementResource::collection($announcements);
    }
}
