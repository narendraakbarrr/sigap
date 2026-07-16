<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // ======================================================
    // AuthController (API)
    // Menangani registrasi, login, logout, dan pengambilan data
    // user saat ini (`me`). Token API dibuat menggunakan Personal Access Token
    // (Sanctum) dengan label `sigap-mobile` untuk penggunaan mobile.
    // Keamanan: validasi input ketat pada registrasi dan login.
    // ======================================================

    /// POST /api/v1/register
    /// - Mendaftarkan user baru, menugaskan role `user`,
    ///   dan mengembalikan token autentikasi.
    /// - Validasi: `name`, `email` unik, `password` (konfirmasi).
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $role = Role::firstOrCreate(['name' => 'user']);
        $user->assignRole($role);
        $token = $user->createToken('sigap-mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->getRoleNames()->first(),
            ],
        ], 201);
    }

    /// POST /api/v1/login
    /// - Mengautentikasi user dan mengembalikan token jika sukses.
    /// - Validasi: `email`, `password`.
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('sigap-mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->getRoleNames()->first(),
            ],
        ]);
    }

    /// POST /api/v1/logout
    /// - Menghapus token akses saat ini untuk user yang meminta.
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    /// GET /api/v1/me
    /// - Mengembalikan data user yang sedang terautentikasi.
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->getRoleNames()->first(),
        ]);
    }
}
