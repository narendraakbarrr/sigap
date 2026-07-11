<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Laporan
            </h2>
            <a href="{{ route('admin.reports.index') }}"
                class="text-sm text-blue-600 hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash message handled by layouts partial --}}

            {{-- Info Laporan --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex flex-col md:flex-row gap-6">

                    {{-- Foto laporan --}}
                    @if($report->photo_path)
                    <div class="w-full md:w-56 flex-shrink-0">
                        <img src="{{ asset('storage/' . $report->photo_path) }}"
                            alt="Foto laporan"
                            class="w-full h-40 md:h-full object-cover rounded-lg border">
                    </div>
                    @endif

                    {{-- Detail --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ \App\Models\Report::STATUS_COLORS[$report->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ \App\Models\Report::STATUS_LABELS[$report->status] ?? $report->status }}
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ \App\Models\Report::URGENCY_COLORS[$report->urgency] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ \App\Models\Report::URGENCY_LABELS[$report->urgency] ?? $report->urgency }}
                            </span>
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            {{ $report->title }}
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">
                            {{ $report->description }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Pelapor</p>
                                <p class="font-medium">{{ $report->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Kategori</p>
                                <p class="font-medium">{{ $report->category->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Lokasi</p>
                                <p class="font-medium">
                                    {{ $report->location_address ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tanggal Lapor</p>
                                <p class="font-medium">
                                    {{ $report->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form update status --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Update Status Laporan</h3>
                <form method="POST"
                    action="{{ route('admin.reports.updateStatus', $report) }}">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Status --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full border rounded-lg px-3 py-2 text-sm
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       @error('status') border-red-500 @enderror">
                                @foreach(\App\Models\Report::STATUS_LABELS as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('status', $report->status) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Urgensi --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">Urgensi</label>
                            <select name="urgency"
                                class="mt-1 w-full border rounded-lg px-3 py-2 text-sm
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       @error('urgency') border-red-500 @enderror">
                                @foreach(\App\Models\Report::URGENCY_LABELS as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('urgency', $report->urgency) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('urgency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Catatan (opsional)
                            </label>
                            <input type="text" name="notes" value="{{ old('notes') }}"
                                class="mt-1 w-full border rounded-lg px-3 py-2 text-sm
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       @error('notes') border-red-500 @enderror"
                                placeholder="Catatan singkat untuk pelapor...">
                            @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Task description --}}
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Deskripsi Tindakan (opsional)
                            </label>
                            <input type="text" name="task_description" value="{{ old('task_description') }}"
                                class="mt-1 w-full border rounded-lg px-3 py-2 text-sm
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       @error('task_description') border-red-500 @enderror"
                                placeholder="Contoh: Jalan sedang diperbaiki oleh tim Dinas PU">
                            @error('task_description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <button type="submit"
                        class="mt-6 w-full sm:w-auto bg-blue-600 hover:bg-blue-700
                               text-white px-6 py-2 rounded-lg text-sm font-medium
                               transition-colors">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Track Record / Riwayat Status --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Track Record Laporan</h3>
                <div class="relative">
                    {{-- Timeline line --}}
                    <div class="absolute left-3 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                    @forelse($report->statusLogs as $log)
                    <div class="relative flex gap-4 mb-6 last:mb-0 pl-8">
                        {{-- Dot --}}
                        <div class="absolute left-0 w-6 h-6 rounded-full flex items-center
                                    justify-center bg-blue-500 text-white text-xs font-bold
                                    border-2 border-white shadow">
                            {{ $loop->iteration }}
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 bg-gray-50 rounded-lg p-4">
                            <div class="flex flex-wrap justify-between items-start gap-1 mb-1">
                                <span class="font-semibold text-sm
                                    {{ collect(\App\Models\Report::STATUS_COLORS)
                                        ->get($log->status, 'text-gray-700') }}">
                                    {{ \App\Models\Report::STATUS_LABELS[$log->status] ?? $log->status }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-500 mb-1">
                                Oleh: <span class="font-medium">{{ $log->changedBy->name }}</span>
                            </p>

                            @if($log->task_description)
                            <div class="mt-2 p-2 bg-blue-50 rounded border-l-4
                                            border-blue-400 text-sm text-blue-800">
                                <span class="font-medium">Tindakan:</span>
                                {{ $log->task_description }}
                            </div>
                            @endif

                            @if($log->notes)
                            <p class="mt-1 text-sm text-gray-600">
                                <span class="font-medium">Catatan:</span> {{ $log->notes }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm pl-8">Belum ada riwayat perubahan.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
