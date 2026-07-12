<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Kelola Pengumuman</h2>
            <a href="{{ route('admin.announcements.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Pengumuman
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4">
        <form method="GET" class="mb-4 flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul pengumuman..."
                   class="border rounded px-3 py-2 w-full md:w-1/3">
            <button type="submit" class="bg-gray-200 px-3 py-2 rounded">Cari</button>
        </form>

        <div class="bg-white rounded shadow divide-y">
            @forelse($announcements as $item)
                <div class="p-4 flex justify-between items-start gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            @if($item->is_pinned)
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">📌 Pinned</span>
                            @endif
                            <h3 class="font-semibold">{{ $item->title }}</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($item->content, 100) }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Oleh {{ $item->creator->name ?? '-' }} • {{ $item->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <div class="flex gap-3 shrink-0">
                        <a href="{{ route('admin.announcements.edit', $item) }}" class="text-blue-600 text-sm">Edit</a>
                        <form action="{{ route('admin.announcements.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-4 text-gray-400">Belum ada pengumuman.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $announcements->links() }}</div>
    </div>
</x-app-layout>
