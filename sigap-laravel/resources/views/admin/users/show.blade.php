<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Detail Pengguna</h2>
            <a href="{{ route('admin.users.index') }}"
            class="text-sm text-blue-600 hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info pengguna --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-medium text-lg">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Role saat ini</p>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $user->hasRole('admin')
                                ? 'bg-purple-100 text-purple-700'
                                : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($user->getRoleNames()->first() ?? '-') }}
                        </span>
                    </div>

                    <div>
                        <p class="text-gray-500">Bergabung</p>
                        <p class="font-medium">
                            {{ $user->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Role & Permission --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <div class="mb-6">
                    <h3 class="font-semibold mb-2">Role</h3>

                    @forelse($user->roles as $role)
                        <span class="inline-block px-3 py-1 mr-2 mb-2 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ ucfirst($role->name) }}
                        </span>
                    @empty
                        <p class="text-gray-400 text-sm">Belum memiliki role.</p>
                    @endforelse
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Hak Akses (Permission)</h3>

                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                        @forelse($user->getAllPermissions() as $permission)
                            <li>{{ $permission->name }}</li>
                        @empty
                            <li class="text-gray-400">
                                Belum ada permission untuk role ini.
                            </li>
                        @endforelse
                    </ul>
                </div>

            </div>

            {{-- Update role --}}
            @if($user->id !== auth()->id())
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">Ubah Role</h3>

                <form method="POST"
                    action="{{ route('admin.users.updateRole', $user) }}"
                    class="flex gap-3">

                    @csrf
                    @method('PUT')

                    <select name="role"
                            class="border rounded-lg px-3 py-2 text-sm">

                        <option value="user"
                            {{ $user->hasRole('user') ? 'selected' : '' }}>
                            User (Warga)
                        </option>

                        <option value="admin"
                            {{ $user->hasRole('admin') ? 'selected' : '' }}>
                            Admin
                        </option>

                    </select>

                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                        Simpan Role
                    </button>

                </form>
            </div>
            @endif

            {{-- Riwayat laporan --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4">
                    Riwayat Laporan ({{ $reports->count() }})
                </h3>

                @forelse($reports as $report)
                    <div class="flex justify-between items-center py-2 border-b last:border-0 text-sm">
                        <span>{{ $report->title }}</span>

                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ \App\Models\Report::STATUS_COLORS[$report->status] ?? '' }}">
                            {{ \App\Models\Report::STATUS_LABELS[$report->status] ?? '-' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Belum ada laporan.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
