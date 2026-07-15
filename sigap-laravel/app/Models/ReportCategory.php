<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    // ======================================================
    // Model: ReportCategory
    // Kategori yang digunakan untuk mengelompokkan laporan.
    // Fields: `name`, `icon`, `description`.
    // Relasi: `reports()` -> hasMany(Report).
    // Digunakan oleh frontend untuk menampilkan pilihan kategori.
    // ======================================================
    protected $fillable = ['name', 'icon', 'description'];

    public function reports()
    {
        return $this->hasMany(Report::class, 'category_id');
    }
}
