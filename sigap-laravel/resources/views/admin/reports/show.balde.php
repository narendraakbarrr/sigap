<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Laporan
            </h2>
            <a href="{{ route('admin.reports.index') }}"
                class="text-sm text-blue-600 hover:underline">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            {{-- Info laporan --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">{{ $report->title }}</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
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
                        <p class="font-medium">{{ $report->location_address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Lapor</p>
                        <p class="font-medium">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Deskripsi</p>
                        <p class="font-medium">{{ $report->description }}</p>
                    </div>
                    @if($report->photo_path)
                    <div class="col-span-2">
                        <p class="text-gray-500 mb-2">Foto</p>
                        <img src="{{ asset('storage/' . $report->photo_path) }}"
                            class="rounded-lg max-h-64 object-cover">
                    </div>
                    @endif
                </div>
            </div>

            {{-- Form update status --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Update Status Laporan</h3>
                <form method="POST"
                    action="{{ route('admin.reports.updateStatus', $report) }}">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="status"
                                class="mt-1 w-full border rounded-lg px-3 py-2">
                                @foreach(['diterima','diproses','selesai','ditolak'] as $s)
                                <option value="{{ $s }}"
                                    {{ $report->status == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Catatan (opsional)
                            </label>
                            <input type="text" name="notes"
                                class="mt-1 w-full border rounded-lg px-3 py-2"
                                placeholder="Catatan untuk pelapor...">
                        </div>
                    </div>
                    <button type="submit"
                        class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg text-sm">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Riwayat status --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Riwayat Status</h3>
                @forelse($report->statusLogs->sortByDesc('created_at') as $log)
                <div class="flex gap-3 mb-3 text-sm">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <span class="font-medium">{{ ucfirst($log->status) }}</span>
                        <span class="text-gray-500">
                            — oleh {{ $log->changedBy->name }}
                            pada {{ $log->created_at->format('d M Y H:i') }}
                        </span>
                        @if($log->notes)
                        <p class="text-gray-600 mt-1">{{ $log->notes }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Belum ada perubahan status.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
