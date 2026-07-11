<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Saya
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash message handled by layouts partial --}}

            {{-- Filter status --}}
            <div class="bg-white shadow-sm rounded-lg p-4 mb-4">
                <form method="GET" action="{{ route('user.reports.index') }}" class="flex gap-3">
                    <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Report::STATUS_LABELS as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                        Filter
                    </button>
                    <a href="{{ route('user.reports.index') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Tabel laporan --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reports as $report)
                            @php
                                $colors = \App\Models\Report::STATUS_COLORS;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">
                                    {{ Str::limit($report->title, 40) }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $report->category->name }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ Str::limit($report->location_address, 30) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs
                                    font-medium {{ $colors[$report->status] ?? '' }}">
                                        {{ \App\Models\Report::STATUS_LABELS[$report->status] ?? $report->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $report->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    Belum ada laporan. Buat laporan pertama Anda!
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
