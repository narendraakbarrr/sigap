@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="sticky top-0 z-50 w-full">
        <div class="bg-green-50 border-b border-green-200 text-green-800 shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="text-sm">{{ session('success') }}</div>
                    <button @click="show = false" aria-label="Tutup" class="ml-4 text-green-800 hover:opacity-80">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="sticky top-0 z-50 w-full">
        <div class="bg-red-50 border-b border-red-200 text-red-800 shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="text-sm">{{ session('error') }}</div>
                    <button @click="show = false" aria-label="Tutup" class="ml-4 text-red-800 hover:opacity-80">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif
