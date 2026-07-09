{{-- Form update status --}}
<div class="bg-white shadow-sm rounded-lg p-6">
    <h3 class="font-semibold mb-4">Update Status Laporan</h3>
    <form method="POST"
        action="{{ route('admin.reports.updateStatus', $report) }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">

            {{-- Status --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Status</label>
                <select name="status"
                    class="mt-1 w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach(\App\Models\Report::STATUS_LABELS as $val => $label)
                    <option value="{{ $val }}"
                        {{ $report->status == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Urgensi --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Urgensi</label>
                <select name="urgency"
                    class="mt-1 w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach(\App\Models\Report::URGENCY_LABELS as $val => $label)
                    <option value="{{ $val }}"
                        {{ $report->urgency == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Catatan --}}
            <div>
                <label class="text-sm font-medium text-gray-700">
                    Catatan (opsional)
                </label>
                <input type="text" name="notes"
                    class="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Catatan singkat untuk pelapor...">
            </div>

            {{-- Task description --}}
            <div>
                <label class="text-sm font-medium text-gray-700">
                    Deskripsi Tindakan (opsional)
                </label>
                <input type="text" name="task_description"
                    class="mt-1 w-full border rounded-lg px-3 py-2 text-sm"
                    placeholder="Contoh: Jalan sedang diperbaiki oleh tim Dinas PU">
            </div>

        </div>
        <button type="submit"
            class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg text-sm">
            Simpan Perubahan
        </button>
    </form>
</div>

{{-- Track Record / Riwayat Status --}}
<div class="bg-white shadow-sm rounded-lg p-6">
    <h3 class="font-semibold mb-4">Track Record Laporan</h3>
    <div class="relative">
        {{-- Timeline line --}}
        <div class="absolute left-3 top-0 bottom-0 w-0.5 bg-gray-200"></div>

        @forelse($report->statusLogs as $log)
        <div class="relative flex gap-4 mb-6 pl-8">
            {{-- Dot --}}
            <div class="absolute left-0 w-6 h-6 rounded-full flex items-center
                        justify-center bg-blue-500 text-white text-xs font-bold
                        border-2 border-white shadow">
                {{ $loop->iteration }}
            </div>

            {{-- Konten --}}
            <div class="flex-1 bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-start mb-1">
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
