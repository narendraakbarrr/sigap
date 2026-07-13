<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Pengumuman</h2>
    </x-slot>

    <div class="py-6 max-w-2xl mx-auto px-4">
        <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Judul</label>
                <input type="text" name="title" value="{{ old('title', $announcement->title) }}" class="w-full border rounded px-3 py-2">
                @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Isi Pengumuman</label>
                <textarea name="content" rows="5" class="w-full border rounded px-3 py-2">{{ old('content', $announcement->content) }}</textarea>
                @error('content') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}>
                <label for="is_pinned" class="text-sm">Pin di atas (prioritas tampil)</label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 rounded border">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
