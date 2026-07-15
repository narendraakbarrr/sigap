<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Report;

class User extends Authenticatable
{
    // ======================================================
    // Model: User
    // Representasi pengguna aplikasi. Menggunakan trait:
    // - `HasApiTokens` untuk token API (Sanctum),
    // - `HasRoles` (Spatie) untuk manajemen role/permission,
    // - `Notifiable` untuk notifikasi.
    // Relasi: `reports()` -> hasMany(Report).
    // Catatan: atribut sensitif seperti `password` disembunyikan pada serialisasi.
    // ======================================================
    use HasApiTokens, HasRoles, Notifiable;

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}
