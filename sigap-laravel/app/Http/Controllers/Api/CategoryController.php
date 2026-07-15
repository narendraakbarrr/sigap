<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;

class CategoryController extends Controller
{
    // ======================================================
    // CategoryController (API)
    // Menyediakan endpoint untuk mengambil daftar kategori laporan.
    // Digunakan oleh frontend untuk mengisi pilihan kategori saat membuat
    // atau mengedit laporan.
    // ======================================================
    /// GET /api/v1/categories
    /// - Return: JSON dengan key `data` berisi koleksi `ReportCategory`.
    public function index()
    {
        $categories = ReportCategory::all();

        return response()->json([
            'data' => $categories,
        ]);
    }
}
