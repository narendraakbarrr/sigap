<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard SIGAP
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-lg font-semibold">
                    Selamat datang, {{ $user->name }}!
                </p>
                <p class="text-gray-600 mt-1">
                    Role: 
                    <span class="font-bold text-orange-600">
                        {{ $user->getRoleNames()->first() ?? '-' }}
                    </span>
                </p>
                <p class="text-green-600 mt-4">
                    ✓ Checkpoint 1 Laravel berhasil
                </p>
            </div>
        </div>
    </div>
</x-app-layout>