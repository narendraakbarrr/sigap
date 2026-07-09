<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Pengguna
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('admin.users.index') }}"
                  class="bg-white shadow-sm rounded-lg p-4 mb-4 flex gap-3">
                <input type="text" name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="border rounded-lg px-3 py-2 flex-1 text-sm">
                <select name="role" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                    Filter
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                    Reset
                </a>
            </form>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Bergabung</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-gray-400">(Anda)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3">
                                @foreach($user->roles as $role)
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $role->name === 'admin'
                                        ? 'bg-purple-100 text-purple-700'
                                        : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-blue-600 hover:underline text-xs">
                                    Detail
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline text-xs">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                class="px-4 py-8 text-center text-gray-400">
                                Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
