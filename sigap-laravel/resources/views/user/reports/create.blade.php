<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Laporan</h2>
            <a href="{{ route('user.reports.index') }}" class="text-sm text-blue-600 hover:underline">Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl p-6">
                <p class="text-sm text-gray-600 mb-6">Isi rincian laporan agar petugas dapat menangani masalah dengan cepat.</p>

                <form action="{{ route('user.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Judul laporan *</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Jalan berlubang depan masjid" class="w-full border rounded-lg px-3 py-2 @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori *</label>
                        <select name="category_id" class="w-full border rounded-lg px-3 py-2 @error('category_id') border-red-500 @enderror">
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Urgensi *</label>
                        <select name="urgency" class="w-full border rounded-lg px-3 py-2">
                            <option value="normal" @selected(old('urgency', 'normal') === 'normal')>Normal</option>
                            <option value="penting" @selected(old('urgency') === 'penting')>Penting</option>
                            <option value="darurat" @selected(old('urgency') === 'darurat')>Darurat 🚨</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="description" rows="4" placeholder="Jelaskan kerusakan atau gangguan secara detail..." class="w-full border rounded-lg px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat lokasi *</label>
                        <input type="text" name="location_address" value="{{ old('location_address') }}" placeholder="Contoh: Jl. Sudirman No. 12, Bandung" class="w-full border rounded-lg px-3 py-2 @error('location_address') border-red-500 @enderror">
                        @error('location_address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Foto kerusakan (opsional)</label>
                        <input type="file" name="photo" accept="image/jpg,image/jpeg,image/png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('photo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
