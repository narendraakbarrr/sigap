<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan Terhapus (Trash)
            </h2>
            <a href="{{ route('admin.reports.index') }}"
               class="text-sm text-blue-600 hover:underline">
                ← Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Flash message handled by layouts partial --}}

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Pelapor</th>
                            <th class="px-4 py-3">Dihapus Pada</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-500">
                                {{ Str::limit($report->title, 40) }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $report->user->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $report->deleted_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3 flex gap-3">
                                {{-- Restore --}}
                                <form method="POST"
                                      action="{{ route('admin.reports.restore', $report->id) }}">
                                    @csrf @method('PUT')
                                    <button type="submit"
                                            class="text-green-600 hover:underline text-xs">
                                        Pulihkan
                                    </button>
                                </form>
                                {{-- Force Delete --}}
                                <form method="POST"
                                      action="{{ route('admin.reports.forceDelete', $report->id) }}"
                                      onsubmit="return confirm('Hapus permanen? Data tidak dapat dikembalikan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline text-xs">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"
                                class="px-4 py-8 text-center text-gray-400">
                                Tidak ada laporan yang dihapus.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
