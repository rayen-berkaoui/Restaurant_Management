<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Restaurant Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-100 text-neutral-950 antialiased">
    @php
        $adminUser = auth()->user();
        $restaurantName = optional(optional($adminUser)->restaurant)->name ?? 'Restaurant Admin';
        $adminInitials = collect(explode(' ', $adminUser->name ?? 'Admin'))
            ->filter()
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->take(2)
            ->implode('');
        $pendingNotifications = \App\Models\Order::whereIn('status', ['pending', 'preparing'])->count();
        $navItems = [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
                'active' => request()->routeIs('dashboard'),
                'icon' => 'dashboard',
            ],
            [
                'label' => 'Categories',
                'route' => 'categories.index',
                'active' => request()->routeIs('categories.*'),
                'icon' => 'categories',
            ],
            [
                'label' => 'Menu Items',
                'route' => 'menu-items.index',
                'active' => request()->routeIs('menu-items.*'),
                'icon' => 'menu',
            ],
            [
                'label' => 'Tables',
                'route' => 'tables.index',
                'active' => request()->routeIs('tables.*'),
                'icon' => 'tables',
            ],
            [
                'label' => 'Orders',
                'route' => 'orders.index',
                'active' => request()->routeIs('orders.*'),
                'icon' => 'orders',
            ],
        ];
    @endphp

    <div
        id="dashboardOverlay"
        class="fixed inset-0 z-40 hidden bg-neutral-950/40 backdrop-blur-sm lg:hidden"
        aria-hidden="true"
    ></div>

    <div class="min-h-screen lg:flex">
        <aside
            id="dashboardSidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-neutral-200 bg-white shadow-2xl shadow-neutral-950/10 transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:shadow-none"
            aria-label="Admin navigation"
        >
            <div class="flex h-20 items-center justify-between border-b border-neutral-100 px-5">
                <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-neutral-950 text-sm font-bold text-white shadow-sm">
                        {{ mb_substr($restaurantName, 0, 1) }}
                    </span>

                    <span class="min-w-0">
                        <span class="block truncate text-sm font-semibold uppercase tracking-[0.18em] text-neutral-400">
                            Admin
                        </span>
                        <span class="block truncate text-lg font-bold text-neutral-950">
                            {{ $restaurantName }}
                        </span>
                    </span>
                </a>

                <button
                    type="button"
                    id="mobileSidebarClose"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-neutral-500 transition hover:bg-neutral-100 hover:text-neutral-950 lg:hidden"
                    aria-label="Close navigation"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                @foreach($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="{{ $item['active'] ? 'bg-neutral-950 text-white shadow-md shadow-neutral-950/15' : 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-950' }} group relative flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold transition"
                    >
                        <span class="{{ $item['active'] ? 'bg-white/15 text-white' : 'bg-white text-neutral-500 ring-1 ring-neutral-200 group-hover:text-neutral-950' }} flex h-9 w-9 items-center justify-center rounded-lg transition">
                            @if($item['icon'] === 'dashboard')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 13h7V4H4v9ZM13 20h7V4h-7v16ZM4 20h7v-5H4v5Z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @elseif($item['icon'] === 'categories')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 6h16M4 12h16M4 18h10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @elseif($item['icon'] === 'menu')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 3v18M10 3v18M14 4h2a4 4 0 0 1 4 4v13" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @elseif($item['icon'] === 'tables')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 10h16M6 10v10M18 10v10M7 4h10l3 6H4l3-6Z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2ZM9 7h6M9 11h6M9 15h4" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            @endif
                        </span>

                        <span>{{ $item['label'] }}</span>

                        @if($item['active'])
                            <span class="ml-auto h-2 w-2 rounded-full bg-emerald-400"></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-neutral-100 p-4">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-neutral-400">
                        Service Status
                    </p>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-neutral-800">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/40"></span>
                            Online
                        </span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-neutral-600 ring-1 ring-neutral-200">
                            {{ $pendingNotifications }} live
                        </span>
                    </div>
                </div>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-30 border-b border-neutral-200 bg-white/90 backdrop-blur">
                <div class="flex min-h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            id="mobileSidebarOpen"
                            class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-700 shadow-sm transition hover:bg-neutral-100 hover:text-neutral-950 lg:hidden"
                            aria-label="Open navigation"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-neutral-500">
                                {{ now()->format('l, F j, Y') }}
                            </p>
                            <h1 class="truncate text-xl font-bold text-neutral-950 sm:text-2xl">
                                @yield('page-title', 'Dashboard')
                            </h1>
                        </div>
                    </div>

                    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                        <button
                            type="button"
                            class="relative inline-flex h-11 w-11 items-center justify-center rounded-lg border border-neutral-200 bg-white text-neutral-700 shadow-sm transition hover:bg-neutral-100 hover:text-neutral-950"
                            aria-label="Notifications"
                        >
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            @if($pendingNotifications > 0)
                                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                    {{ $pendingNotifications > 9 ? '9+' : $pendingNotifications }}
                                </span>
                            @endif
                        </button>

                        <div class="hidden items-center gap-3 rounded-lg border border-neutral-200 bg-white py-2 pl-2 pr-4 shadow-sm sm:flex">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600 text-xs font-bold text-white">
                                {{ $adminInitials ?: 'A' }}
                            </span>
                            <span class="min-w-0">
                                <span class="block max-w-36 truncate text-sm font-bold text-neutral-950">
                                    {{ $adminUser->name ?? 'Admin' }}
                                </span>
                                <span class="block text-xs font-medium text-neutral-500">
                                    Administrator
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('dashboardOverlay');
            const openButton = document.getElementById('mobileSidebarOpen');
            const closeButton = document.getElementById('mobileSidebarClose');

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            };

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            };

            openButton?.addEventListener('click', openSidebar);
            closeButton?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
        })();
    </script>
</body>
</html>
