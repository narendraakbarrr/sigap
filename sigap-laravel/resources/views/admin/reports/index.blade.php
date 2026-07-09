<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Laporan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash message --}}
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            {{-- Search & Filter --}}
            <div class="bg-white shadow-sm rounded-lg p-4 mb-4">
                <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, lokasi, atau pelapor..."
                        class="border rounded-lg px-3 py-2 flex-1 min-w-48 text-sm">

                    <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Status</option>
                        @foreach (['diterima', 'diproses', 'selesai', 'ditolak'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>

                    <select name="category_id" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.reports.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                        Reset
                    </a>
                </form>
            </div>
            <div class="flex justify-end mb-2">
                <a href="{{ route('admin.reports.trash') }}"
                    class="text-sm text-red-600 hover:underline flex items-center gap-1">
                    🗑 Lihat Laporan Terhapus
                </a>
            </div>

            {{-- Tabel --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Pelapor</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">
                                {{ Str::limit($report->title, 40) }}
                            </td>
                            <td class="px-4 py-3">{{ $report->user->name }}</td>
                            <td class="px-4 py-3">{{ $report->category->name }}</td>
                            <td class="px-4 py-3">
                                @php
                                $colors = [
                                'diterima' => 'bg-blue-100 text-blue-700',
                                'diproses' => 'bg-yellow-100 text-yellow-700',
                                'selesai' => 'bg-green-100 text-green-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                                ];
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $colors[$report->status] ?? '' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('admin.reports.show', $report) }}"
                                    class="text-blue-600 hover:underline text-xs">
                                    Detail
                                </a>
                                <form method="POST" action="{{ route('admin.reports.destroy', $report) }}"
                                    onsubmit="return confirm('Hapus laporan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                Tidak ada laporan ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>


                {{-- Pagination --}}
                <div class="px-4 py-3 border-t">
                    {{ $reports->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>