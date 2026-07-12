<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderByDesc('is_pinned')->latest()->get();

        return AnnouncementResource::collection($announcements);
    }
}
