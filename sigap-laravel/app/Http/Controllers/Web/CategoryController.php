<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ReportCategory::withCount('reports');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:report_categories',
            'icon'        => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        ReportCategory::create($request->only('name', 'icon', 'description'));

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ReportCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ReportCategory $category)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:report_categories,name,' . $category->id,
            'icon'        => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        $category->update($request->only('name', 'icon', 'description'));

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ReportCategory $category)
    {
        if ($category->reports()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki laporan.');
        }

        $category->delete();
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}