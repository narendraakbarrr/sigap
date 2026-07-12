{{-- ganti header --}}
<h2 class="font-semibold text-xl text-gray-800">Edit Pengumuman</h2>

{{-- ganti form tag --}}
<form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    @method('PUT')

{{-- ganti value tiap field jadi terisi data lama --}}
<input type="text" name="title" value="{{ old('title', $announcement->title) }}" class="w-full border rounded px-3 py-2">
...
<textarea name="content" rows="5" class="w-full border rounded px-3 py-2">{{ old('content', $announcement->content) }}</textarea>
...
<input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}>
