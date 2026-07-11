<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin — SIGAP
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Statistik utama --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([['label' => 'Total Laporan', 'value' => $stats['total'], 'color' => 'blue'], ['label' => \App\Models\Report::STATUS_LABELS[\App\Models\Report::STATUS_IN_PROGRESS], 'value' => $stats['in_progress'], 'color' => 'yellow'], ['label' => 'Selesai', 'value' => $stats['selesai'], 'color' => 'green'], ['label' => 'Darurat', 'value' => $stats['darurat'], 'color' => 'red']] as $card)
                    <div
                        class="bg-white rounded-lg shadow-sm p-5 border-l-4
                            border-{{ $card['color'] }}-500">
                        <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-{{ $card['color'] }}-600 mt-1">
                            {{ $card['value'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Row 2: Status breakdown + Grafik per kategori --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Status breakdown --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">
                        Laporan per Status
                    </h3>
                    @foreach (\App\Models\Report::STATUS_LABELS as $key => $label)
                        @php
                            $count = $stats[$key] ?? 0;
                            $total = $stats['total'] ?: 1;
                        @endphp
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span>{{ $label }}</span>
                                <span class="font-medium">{{ $count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full bg-blue-500" style="width: {{ ($count / $total) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Grafik per kategori (bar chart pakai CSS) --}}
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">
                        Laporan per Kategori
                    </h3>
                    @php $maxKat = $topCategories->max('count') ?: 1; @endphp
                    <div x-data="{ showAll: false }">
                        @foreach ($topCategories as $kat)
                            <div class="mb-3">
                                <div class="flex justify-between text-sm mb-1">
                                    <span>{{ $kat['name'] }}</span>
                                    <span class="font-medium">{{ $kat['count'] }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-orange-500"
                                        style="width: {{ ($kat['count'] / $maxKat) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($otherCategories->count())
                            <div x-show="showAll" x-transition class="mt-2 space-y-2">
                                @foreach($otherCategories as $kat)
                                    <div class="mb-3">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>{{ $kat['name'] }}</span>
                                            <span class="font-medium">{{ $kat['count'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="h-2 rounded-full bg-orange-500"
                                                style="width: {{ ($kat['count'] / $maxKat) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button @click="showAll = !showAll" type="button" class="text-sm text-blue-600 mt-2">
                                <span x-show="!showAll">Tampilkan {{ $otherCategories->count() }} kategori lainnya</span>
                                <span x-show="showAll" x-cloak>Sembunyikan</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Laporan Darurat yang belum selesai --}}
            @if ($laporanDarurat->count() > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="font-semibold text-red-700 mb-3 flex items-center gap-2">
                        <span>⚠️</span> Laporan Darurat Belum Selesai
                    </h3>
                    <div class="space-y-2">
                        @foreach ($laporanDarurat as $lap)
                            <div
                                class="flex justify-between items-center bg-white
                                rounded-lg p-3 shadow-sm">
                                <div>
                                    <p class="font-medium text-sm">{{ $lap->title }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $lap->user->name }} ·
                                        {{ $lap->category->name }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-xs px-2 py-1 rounded-full
                                bg-yellow-100 text-yellow-700">
                                        {{ \App\Models\Report::STATUS_LABELS[$lap->status] }}
                                    </span>
                                    <a href="{{ route('admin.reports.show', $lap) }}"
                                        class="text-xs text-blue-600 hover:underline">
                                        Detail →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Laporan terbaru --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Laporan Terbaru</h3>
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-blue-600 hover:underline">
                        Lihat semua →
                    </a>
                </div>
                <table class="w-full text-sm">
                    <thead class="text-gray-500 border-b">
                        <tr>
                            <th class="text-left pb-2">Judul</th>
                            <th class="text-left pb-2">Pelapor</th>
                            <th class="text-left pb-2">Urgensi</th>
                            <th class="text-left pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($laporanTerbaru as $lap)
                            <tr>
                                <td class="py-2">
                                    <a href="{{ route('admin.reports.show', $lap) }}" class="hover:text-blue-600">
                                        {{ Str::limit($lap->title, 35) }}
                                    </a>
                                </td>
                                <td class="py-2 text-gray-500">
                                    {{ $lap->user->name }}
                                </td>
                                <td class="py-2">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs
                                    {{ \App\Models\Report::URGENCY_COLORS[$lap->urgency] ?? '' }}">
                                        {{ \App\Models\Report::URGENCY_LABELS[$lap->urgency] ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs
                                    {{ \App\Models\Report::STATUS_COLORS[$lap->status] ?? '' }}">
                                        {{ \App\Models\Report::STATUS_LABELS[$lap->status] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
