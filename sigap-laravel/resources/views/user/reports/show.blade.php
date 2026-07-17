<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Laporan</h2>
            <div class="flex gap-2">
                <a href="{{ route('user.reports.index') }}" class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-200 hover:text-gray-900 transition">Kembali</a>
                @if($report->status === \App\Models\Report::STATUS_DITERIMA)
                    <a href="{{ route('user.reports.edit', $report) }}" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700">Edit</a>
                @endif
                <form action="{{ route('user.reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-red-700">Hapus</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    @if($report->photo_path)
                        <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto laporan" class="w-full md:w-72 h-56 object-cover rounded-lg border">
                    @else
                        <div class="w-full md:w-72 h-56 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-gray-400">
                            Tidak ada foto
                        </div>
                    @endif

                    <div class="flex-1 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ \App\Models\Report::STATUS_COLORS[$report->status] ?? '' }}">
                                {{ \App\Models\Report::STATUS_LABELS[$report->status] ?? $report->status }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ \App\Models\Report::URGENCY_COLORS[$report->urgency] ?? '' }}">
                                {{ \App\Models\Report::URGENCY_LABELS[$report->urgency] ?? $report->urgency }}
                            </span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900">{{ $report->title }}</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->description }}</p>

                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500">Kategori</dt>
                                <dd class="font-semibold text-gray-800">{{ $report->category->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Lokasi</dt>
                                <dd class="font-semibold text-gray-800">{{ $report->location_address }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Tanggal dibuat</dt>
                                <dd class="font-semibold text-gray-800">{{ $report->created_at->format('d M Y, H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Status saat ini</dt>
                                <dd class="font-semibold text-gray-800">{{ \App\Models\Report::STATUS_LABELS[$report->status] ?? $report->status }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
