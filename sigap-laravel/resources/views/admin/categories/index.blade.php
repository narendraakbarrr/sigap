<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Kategori
            </h2>
            <a href="{{ route('admin.categories.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                {{-- Flash message handled by layouts partial --}}
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.categories.index') }}"
                  class="mb-4 flex gap-2">
                <input type="text" name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama kategori..."
                       class="border rounded-lg px-3 py-2 flex-1 text-sm">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                    Cari
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                    Reset
                </a>
            </form>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Icon</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3">Jumlah Laporan</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $cat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $cat->name }}</td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $cat->icon ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ Str::limit($cat->description, 50) ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold">
                                    {{ $cat->reports_count }}
                                </span> laporan
                            </td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="text-blue-600 hover:underline text-xs">
                                    Edit
                                </a>
                                @if($cat->reports_count == 0)
                                <form method="POST"
                                      action="{{ route('admin.categories.destroy', $cat) }}"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline text-xs">
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-300 text-xs">Hapus</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="px-4 py-8 text-center text-gray-400">
                                Tidak ada kategori.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
