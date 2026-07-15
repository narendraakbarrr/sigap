<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // ======================================================
    // ProfileController
    // Mengelola endpoint profil pengguna (tampilan dan pembaruan).
    // Tanggung jawab: membaca data pengguna dari request yang diautentikasi
    // dan memperbarui atribut profil yang sederhana (saat ini hanya nama).
    // Digunakan oleh: frontend Mobile/SPA untuk menampilkan dan mengedit profil.
    // Dependency penting: middleware autentikasi (sanctum/passport/session).
    // ======================================================
    // GET /api/v1/profile
    /// Mengembalikan data profil pengguna yang sedang terautentikasi.
    /// - Parameter: `Request $request` (mengandung user yang terautentikasi)
    /// - Return: JSON berisi `id`, `name`, `email`, dan `role` pertama user.
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->getRoleNames()->first(),
        ]);
    }

    // PUT /api/v1/profile
    /// Memperbarui profil pengguna.
    /// - Validasi: `name` wajib, string, max 255.
    /// - Efek samping: memperbarui record pengguna di tabel `users`.
    /// - Return: JSON profil terkini setelah pembaruan.
    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->update(['name' => $request->name]);

        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->getRoleNames()->first(),
        ]);
    }
}
