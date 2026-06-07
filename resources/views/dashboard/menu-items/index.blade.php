@extends('layouts.dashboard')

@section('content')

@php
    $groupedItems = $items->groupBy(fn ($item) => optional($item->category)->name ?? 'Uncategorized');
    $availableCount = $items->where('available', true)->count();
    $unavailableCount = $items->where('available', false)->count();
@endphp

<div
    id="menuItemsDashboard"
    class="space-y-6"
>
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">
                Restaurant Menu
            </p>

            <h1 class="mt-1 text-3xl font-bold text-gray-950">
                Menu Items
            </h1>

            <p class="mt-2 text-sm text-gray-500">
                {{ $items->count() }} products across {{ $groupedItems->count() }} categories
            </p>
        </div>

        <a
            href="{{ route('menu-items.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-gray-800 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Add Item
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Total Items
            </p>
            <p class="mt-2 text-3xl font-bold text-gray-950">
                {{ $items->count() }}
            </p>
        </div>

        <div class="rounded-lg border border-emerald-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Available
            </p>
            <p class="mt-2 text-3xl font-bold text-emerald-700">
                {{ $availableCount }}
            </p>
        </div>

        <div class="rounded-lg border border-rose-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">
                Unavailable
            </p>
            <p class="mt-2 text-3xl font-bold text-rose-700">
                {{ $unavailableCount }}
            </p>
        </div>
    </div>

    <div class="sticky top-0 z-20 rounded-lg border border-gray-200 bg-white/95 p-4 shadow-sm backdrop-blur">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>

                <input
                    id="menuSearch"
                    type="search"
                    placeholder="Search by product name..."
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 py-3 pl-12 pr-4 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-gray-400 focus:bg-white focus:ring-4 focus:ring-gray-100"
                >
            </div>

            <div class="flex rounded-lg border border-gray-200 bg-gray-50 p-1">
                <button
                    type="button"
                    data-filter="all"
                    class="availability-filter rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-950 shadow-sm transition"
                >
                    All
                </button>

                <button
                    type="button"
                    data-filter="available"
                    class="availability-filter rounded-lg px-4 py-2 text-sm font-semibold text-gray-500 transition hover:text-gray-900"
                >
                    Available
                </button>

                <button
                    type="button"
                    data-filter="unavailable"
                    class="availability-filter rounded-lg px-4 py-2 text-sm font-semibold text-gray-500 transition hover:text-gray-900"
                >
                    Unavailable
                </button>
            </div>
        </div>
    </div>

    <div
        id="emptyMenuState"
        class="hidden rounded-lg border border-dashed border-gray-300 bg-white p-10 text-center shadow-sm"
    >
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15ZM21 21l-4.35-4.35" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h2 class="mt-4 text-lg font-semibold text-gray-950">
            No matching menu items
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Try another product name or availability filter.
        </p>
    </div>

    <div class="space-y-5">
        @forelse($groupedItems as $categoryName => $categoryItems)
            <details
                class="category-section group overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
                data-category-section
                open
            >
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 transition hover:bg-gray-50">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-950 text-sm font-bold uppercase text-white">
                                {{ mb_substr($categoryName, 0, 1) }}
                            </span>

                            <div class="min-w-0">
                                <h2 class="truncate text-lg font-bold text-gray-950">
                                    {{ $categoryName }}
                                </h2>

                                <p class="text-sm text-gray-500">
                                    <span data-visible-count>{{ $categoryItems->count() }}</span> of {{ $categoryItems->count() }} items visible
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                            {{ $categoryItems->count() }} items
                        </span>

                        <span class="rounded-full border border-gray-200 p-2 text-gray-500 transition group-open:rotate-180">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                </summary>

                <div class="grid gap-3 p-4">
                    @foreach($categoryItems as $item)
                        <div
                            class="menu-item-card group/item flex flex-col gap-4 rounded-lg border border-gray-100 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-gray-200 hover:shadow-md md:flex-row md:items-center"
                            data-menu-item
                            data-name="{{ Str::lower($item->name) }}"
                            data-status="{{ $item->available ? 'available' : 'unavailable' }}"
                        >
                            <div class="h-24 w-full shrink-0 overflow-hidden rounded-lg bg-gray-100 md:h-20 md:w-20">
                                @if($item->image)
                                    <img
                                        src="{{ asset('storage/' . $item->image) }}"
                                        alt="{{ $item->name }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover/item:scale-105"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs font-medium text-gray-400">
                                        No Image
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-bold text-gray-950">
                                            {{ $item->name }}
                                        </h3>

                                        <p class="mt-1 line-clamp-2 text-sm leading-6 text-gray-500">
                                            {{ $item->description ?: 'No description added yet.' }}
                                        </p>
                                    </div>

                                    <div class="flex shrink-0 items-center gap-2 md:justify-end">
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-bold text-gray-950">
                                            {{ number_format((float) $item->price, 2) }} DT
                                        </span>

                                        @if($item->available)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-100">
                                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                                Unavailable
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-2 border-t border-gray-100 pt-4 md:border-l md:border-t-0 md:pl-4 md:pt-0">
                                <a
                                    href="{{ route('menu-items.edit', $item->id) }}"
                                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 text-sm font-semibold text-gray-700 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-950 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m16.86 3.49 3.65 3.65M4 20h3.65L19.43 8.22a2.58 2.58 0 0 0-3.65-3.65L4 16.35V20Z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Edit
                                </a>

                                <form
                                    action="{{ route('menu-items.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this menu item?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 text-sm font-semibold text-rose-700 ring-1 ring-rose-100 transition hover:bg-rose-100 hover:text-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2"
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18M8 6V4h8v2M10 11v6M14 11v6M5 6l1 14h12l1-14" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </details>
        @empty
            <div class="rounded-lg border border-dashed border-gray-300 bg-white p-10 text-center shadow-sm">
                <h2 class="text-lg font-semibold text-gray-950">
                    No menu items yet
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Add your first product to start building the restaurant menu.
                </p>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.getElementById('menuItemsDashboard');

        if (!root) {
            return;
        }

        const searchInput = root.querySelector('#menuSearch');
        const filterButtons = [...root.querySelectorAll('.availability-filter')];
        const categorySections = [...root.querySelectorAll('[data-category-section]')];
        const emptyState = root.querySelector('#emptyMenuState');
        let activeFilter = 'all';

        const activeClasses = ['bg-white', 'text-gray-950', 'shadow-sm'];
        const inactiveClasses = ['text-gray-500', 'hover:text-gray-900'];

        const refreshFilters = () => {
            const searchTerm = searchInput.value.trim().toLowerCase();
            let totalVisible = 0;

            categorySections.forEach((section) => {
                const cards = [...section.querySelectorAll('[data-menu-item]')];
                let visibleInSection = 0;

                cards.forEach((card) => {
                    const matchesSearch = card.dataset.name.includes(searchTerm);
                    const matchesStatus = activeFilter === 'all' || card.dataset.status === activeFilter;
                    const shouldShow = matchesSearch && matchesStatus;

                    card.classList.toggle('hidden', !shouldShow);

                    if (shouldShow) {
                        visibleInSection += 1;
                        totalVisible += 1;
                    }
                });

                section.classList.toggle('hidden', visibleInSection === 0);

                const visibleCount = section.querySelector('[data-visible-count]');

                if (visibleCount) {
                    visibleCount.textContent = visibleInSection;
                }
            });

            if (emptyState) {
                emptyState.classList.toggle('hidden', totalVisible > 0);
            }
        };

        filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.filter;

                filterButtons.forEach((filterButton) => {
                    const isActive = filterButton === button;

                    filterButton.classList.toggle(activeClasses[0], isActive);
                    filterButton.classList.toggle(activeClasses[1], isActive);
                    filterButton.classList.toggle(activeClasses[2], isActive);
                    filterButton.classList.toggle(inactiveClasses[0], !isActive);
                    filterButton.classList.toggle(inactiveClasses[1], !isActive);
                });

                refreshFilters();
            });
        });

        searchInput.addEventListener('input', refreshFilters);
    });
</script>

@endsection
