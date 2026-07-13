<aside class="hidden lg:block w-64 bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800 min-h-screen">
    <div class="px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="text-2xl font-extrabold text-orange-600">SIGAP</div>
        </a>
    </div>
    <nav class="px-2 pb-6">
        <div class="space-y-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>

            @role('admin')
                <x-nav-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">Laporan</x-nav-link>
                <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">Kategori</x-nav-link>
                <x-nav-link :href="route('admin.announcements.index')" :active="request()->routeIs('admin.announcements.*')">Pengumuman</x-nav-link>
                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Pengguna</x-nav-link>
            @endrole

            @role('user')
                <x-nav-link :href="route('user.reports.index')" :active="request()->routeIs('user.reports.*')">Laporan Saya</x-nav-link>
            @endrole
        </div>
    </nav>
    <div class="px-6 mt-auto hidden lg:block">
        <div class="text-xs text-gray-500">Version</div>
        <div class="text-sm text-gray-700">v{{ app()->version() }}</div>
    </div>
</aside>
