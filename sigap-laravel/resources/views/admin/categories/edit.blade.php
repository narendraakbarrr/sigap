<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Kategori
            </h2>
            <a href="{{ route('admin.categories.index') }}"
               class="text-sm text-blue-600 hover:underline">← Kembali</a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Nama Kategori *
                            </label>
                            <input type="text" name="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="mt-1 w-full border rounded-lg px-3 py-2
                                          @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Icon (opsional)
                            </label>
                            <input type="text" name="icon"
                                   value="{{ old('icon', $category->icon) }}"
                                   placeholder="Contoh: road, lightbulb, water"
                                   class="mt-1 w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">
                                Deskripsi (opsional)
                            </label>
                            <textarea name="description" rows="3"
                                      class="mt-1 w-full border rounded-lg px-3 py-2">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>
                    <button type="submit"
                            class="mt-6 w-full bg-blue-600 text-white py-2
                                   rounded-lg text-sm font-medium">
                        Update Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>