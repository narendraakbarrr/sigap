<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard — SIGAP
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Greeting --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-lg font-semibold">
                    Selamat datang, {{ Auth::user()->name }}!
                </p>
                <p class="text-gray-500 text-sm mt-1">
                    Pantau status laporan Anda di sini.
                </p>
            </div>

            <div class="bg-white rounded shadow p-4 mb-6">
                <h3 class="font-semibold mb-3">📢 Pengumuman</h3>
                @forelse($announcements as $item)
                    <div class="border-b last:border-0 py-2">
                        <p class="font-medium text-sm">
                            @if ($item->is_pinned)
                                📌
                            @endif {{ $item->title }}
                        </p>
                        <p class="text-sm text-gray-600">{{ Str::limit($item->content, 120) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada pengumuman.</p>
                @endforelse
            </div>

            {{-- Statistik warga --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([['label' => 'Total Laporan', 'value' => $stats['total'], 'color' => 'blue'], ['label' => 'Diterima', 'value' => $stats['diterima'], 'color' => 'purple'], ['label' => \App\Models\Report::STATUS_LABELS['in_progress'], 'value' => $stats['in_progress'], 'color' => 'yellow'], ['label' => 'Selesai', 'value' => $stats['selesai'], 'color' => 'green']] as $card)
                    <div
                        class="bg-white rounded-lg shadow-sm p-4 border-l-4
                            border-{{ $card['color'] }}-500">
                        <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
                        <p class="text-2xl font-bold text-{{ $card['color'] }}-600 mt-1">
                            {{ $card['value'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Laporan terbaru milik warga --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700">Laporan Terbaru Saya</h3>
                    <a href="{{ route('user.reports.index') }}" class="text-sm text-blue-600 hover:underline">
                        Lihat semua →
                    </a>
                </div>
                @forelse($laporanTerbaru as $lap)
                    <div
                        class="flex justify-between items-center py-3 border-b
                            last:border-0">
                        <div>
                            <p class="font-medium text-sm">{{ $lap->title }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $lap->category->name }} ·
                                {{ $lap->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium
                        {{ \App\Models\Report::STATUS_COLORS[$lap->status] ?? '' }}">
                            {{ \App\Models\Report::STATUS_LABELS[$lap->status] ?? '-' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">
                        Belum ada laporan. Tekan "Laporan Saya" untuk membuat laporan baru.
                    </p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
