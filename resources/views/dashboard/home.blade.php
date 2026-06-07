@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Restaurant Overview')

@section('content')

@php
    use App\Models\MenuCategory;
    use App\Models\MenuItem;
    use App\Models\Order;
    use App\Models\OrderItem;
    use App\Models\RestaurantTable;
    use Illuminate\Support\Facades\DB;

    $today = now()->toDateString();
    $startOfWeek = now()->startOfWeek();
    $endOfWeek = now()->endOfWeek();
    $startOfMonth = now()->startOfMonth();
    $endOfMonth = now()->endOfMonth();
    $yesterday = now()->subDay()->toDateString();
    $previousWeekStart = now()->subWeek()->startOfWeek();
    $previousWeekEnd = now()->subWeek()->endOfWeek();
    $previousMonthStart = now()->subMonth()->startOfMonth();
    $previousMonthEnd = now()->subMonth()->endOfMonth();

    $todaysOrders = Order::whereDate('created_at', $today)->count();
    $todaysRevenue = Order::where('status', 'completed')->whereDate('created_at', $today)->sum('total_price');
    $yesterdaysOrders = Order::whereDate('created_at', $yesterday)->count();
    $yesterdaysRevenue = Order::where('status', 'completed')->whereDate('created_at', $yesterday)->sum('total_price');

    $weeklyRevenue = Order::where('status', 'completed')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_price');
    $monthlyRevenue = Order::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_price');
    $previousWeeklyRevenue = Order::where('status', 'completed')->whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])->sum('total_price');
    $previousMonthlyRevenue = Order::where('status', 'completed')->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->sum('total_price');

    $occupiedTables = RestaurantTable::whereHas('orders', fn ($query) => $query->whereIn('status', ['pending', 'preparing']))->count();
    $activeTables = $occupiedTables;
    $availableTables = max($tablesCount - $occupiedTables, 0);
    $tableOccupancyRate = $tablesCount > 0 ? round(($occupiedTables / $tablesCount) * 100) : 0;

    $totalCategories = MenuCategory::count();
    $availableItems = MenuItem::where('available', true)->count();
    $unavailableItems = MenuItem::where('available', false)->count();

    $bestSellingItems = OrderItem::query()
        ->select('menu_item_id', DB::raw('SUM(quantity) as orders_count'))
        ->with('menuItem')
        ->groupBy('menu_item_id')
        ->orderByDesc('orders_count')
        ->take(5)
        ->get();

    $activityOrders = Order::latest()->take(6)->get();

    $trend = function ($current, $previous) {
        if ((float) $previous === 0.0) {
            return (float) $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100);
    };

    $formatTrend = fn ($value) => ($value >= 0 ? '+' : '') . $value . '%';

    $statusStyles = [
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'preparing' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];

    $statusDots = [
        'pending' => 'bg-amber-500',
        'preparing' => 'bg-sky-500',
        'completed' => 'bg-emerald-500',
        'cancelled' => 'bg-rose-500',
    ];

    $kpis = [
        [
            'label' => 'Total Orders',
            'value' => number_format($ordersCount),
            'trend' => $formatTrend($trend($todaysOrders, $yesterdaysOrders)),
            'helper' => 'vs yesterday',
            'accent' => 'bg-neutral-950 text-white',
            'indicator' => 'bg-neutral-950',
            'icon' => 'orders',
        ],
        [
            'label' => "Today's Orders",
            'value' => number_format($todaysOrders),
            'trend' => $formatTrend($trend($todaysOrders, $yesterdaysOrders)),
            'helper' => 'service pace',
            'accent' => 'bg-sky-600 text-white',
            'indicator' => 'bg-sky-500',
            'icon' => 'clock',
        ],
        [
            'label' => 'Total Revenue',
            'value' => number_format((float) $revenue, 2) . ' DT',
            'trend' => $formatTrend($trend($todaysRevenue, $yesterdaysRevenue)),
            'helper' => 'completed sales',
            'accent' => 'bg-emerald-600 text-white',
            'indicator' => 'bg-emerald-500',
            'icon' => 'revenue',
        ],
        [
            'label' => "Today's Revenue",
            'value' => number_format((float) $todaysRevenue, 2) . ' DT',
            'trend' => $formatTrend($trend($todaysRevenue, $yesterdaysRevenue)),
            'helper' => 'vs yesterday',
            'accent' => 'bg-violet-600 text-white',
            'indicator' => 'bg-violet-500',
            'icon' => 'today',
        ],
        [
            'label' => 'Pending Orders',
            'value' => number_format($pendingOrders),
            'trend' => $pendingOrders > 0 ? 'Live' : 'Clear',
            'helper' => 'needs action',
            'accent' => 'bg-amber-500 text-white',
            'indicator' => 'bg-amber-500',
            'icon' => 'pending',
        ],
        [
            'label' => 'Preparing Orders',
            'value' => number_format($preparingOrders),
            'trend' => $preparingOrders > 0 ? 'Active' : 'Quiet',
            'helper' => 'in kitchen',
            'accent' => 'bg-sky-500 text-white',
            'indicator' => 'bg-sky-500',
            'icon' => 'preparing',
        ],
        [
            'label' => 'Completed Orders',
            'value' => number_format($completedOrders),
            'trend' => $completedOrders > 0 ? 'Closed' : 'None',
            'helper' => 'fulfilled',
            'accent' => 'bg-emerald-500 text-white',
            'indicator' => 'bg-emerald-500',
            'icon' => 'completed',
        ],
        [
            'label' => 'Cancelled Orders',
            'value' => number_format($cancelledOrders),
            'trend' => $cancelledOrders > 0 ? 'Review' : 'Stable',
            'helper' => 'exceptions',
            'accent' => 'bg-rose-500 text-white',
            'indicator' => 'bg-rose-500',
            'icon' => 'cancelled',
        ],
    ];

    $revenueSeries = [
        ['label' => 'Daily Revenue', 'value' => (float) $todaysRevenue, 'trend' => $formatTrend($trend($todaysRevenue, $yesterdaysRevenue)), 'color' => 'bg-sky-500'],
        ['label' => 'Weekly Revenue', 'value' => (float) $weeklyRevenue, 'trend' => $formatTrend($trend($weeklyRevenue, $previousWeeklyRevenue)), 'color' => 'bg-emerald-500'],
        ['label' => 'Monthly Revenue', 'value' => (float) $monthlyRevenue, 'trend' => $formatTrend($trend($monthlyRevenue, $previousMonthlyRevenue)), 'color' => 'bg-violet-500'],
    ];
    $maxRevenue = max(collect($revenueSeries)->pluck('value')->max(), 1);
@endphp

<div class="space-y-6">
    <section class="rounded-lg border border-neutral-200 bg-gradient-to-br from-white via-white to-emerald-50 p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-neutral-400">
                    Command Center
                </p>
                <h2 class="mt-2 text-3xl font-bold text-neutral-950">
                    Restaurant operations
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-neutral-500">
                    {{ $todaysOrders }} orders today · {{ $pendingOrders + $preparingOrders }} active tickets · {{ $availableTables }} tables available
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-950 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-neutral-800 hover:shadow-md">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2ZM9 7h6M9 11h6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    View Orders
                </a>
                <a href="{{ route('menu-items.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-3 text-sm font-bold text-neutral-800 shadow-sm transition hover:-translate-y-0.5 hover:bg-neutral-50 hover:shadow-md">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Add Item
                </a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($kpis as $kpi)
            <article class="group rounded-lg border border-neutral-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-neutral-300 hover:shadow-lg">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-neutral-500">
                            {{ $kpi['label'] }}
                        </p>
                        <p class="mt-3 text-3xl font-bold tracking-tight text-neutral-950">
                            {{ $kpi['value'] }}
                        </p>
                    </div>

                    <span class="{{ $kpi['accent'] }} flex h-11 w-11 shrink-0 items-center justify-center rounded-lg shadow-sm transition group-hover:scale-105">
                        @if($kpi['icon'] === 'revenue')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @elseif($kpi['icon'] === 'clock')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 6v6l4 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @elseif($kpi['icon'] === 'completed')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m5 13 4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @elseif($kpi['icon'] === 'cancelled')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2ZM9 7h6M9 11h6M9 15h4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @endif
                    </span>
                </div>

                <div class="mt-5 flex items-center justify-between gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-neutral-100 px-3 py-1 text-xs font-bold text-neutral-700">
                        <span class="{{ $kpi['indicator'] }} h-1.5 w-1.5 rounded-full"></span>
                        {{ $kpi['trend'] }}
                    </span>
                    <span class="text-xs font-medium text-neutral-400">
                        {{ $kpi['helper'] }}
                    </span>
                </div>
            </article>
        @endforeach
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-1">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-neutral-500">Real-time Restaurant Status</p>
                    <h3 class="mt-1 text-xl font-bold text-neutral-950">Tables</h3>
                </div>
                <span class="rounded-lg bg-emerald-50 px-3 py-2 text-sm font-bold text-emerald-700 ring-1 ring-emerald-100">
                    {{ $tableOccupancyRate }}% occupied
                </span>
            </div>

            <div class="mt-6 space-y-5">
                <div>
                    <div class="flex items-center justify-between text-sm font-semibold">
                        <span class="text-neutral-600">Active Tables</span>
                        <span class="text-neutral-950">{{ $activeTables }} / {{ $tablesCount }}</span>
                    </div>
                    <div class="mt-2 h-2 rounded-full bg-neutral-100">
                        <div class="h-2 rounded-full bg-neutral-950" style="width: {{ $tablesCount > 0 ? min(($activeTables / $tablesCount) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between text-sm font-semibold">
                        <span class="text-neutral-600">Occupied Tables</span>
                        <span class="text-neutral-950">{{ $occupiedTables }}</span>
                    </div>
                    <div class="mt-2 h-2 rounded-full bg-neutral-100">
                        <div class="h-2 rounded-full bg-amber-500" style="width: {{ $tablesCount > 0 ? min(($occupiedTables / $tablesCount) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between text-sm font-semibold">
                        <span class="text-neutral-600">Available Tables</span>
                        <span class="text-neutral-950">{{ $availableTables }}</span>
                    </div>
                    <div class="mt-2 h-2 rounded-full bg-neutral-100">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $tablesCount > 0 ? min(($availableTables / $tablesCount) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-2">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-neutral-500">Revenue Section</p>
                    <h3 class="mt-1 text-xl font-bold text-neutral-950">Sales performance</h3>
                </div>
                <span class="rounded-lg border border-neutral-200 bg-neutral-50 px-3 py-2 text-sm font-bold text-neutral-700">
                    Completed orders only
                </span>
            </div>

            <div class="mt-6 space-y-5">
                @foreach($revenueSeries as $series)
                    <div class="grid gap-3 sm:grid-cols-[150px_1fr_100px] sm:items-center">
                        <div>
                            <p class="text-sm font-bold text-neutral-800">{{ $series['label'] }}</p>
                            <p class="text-xs font-semibold text-neutral-400">{{ $series['trend'] }}</p>
                        </div>
                        <div class="h-4 overflow-hidden rounded-full bg-neutral-100">
                            <div class="{{ $series['color'] }} h-4 rounded-full transition-all duration-700" style="width: {{ max(($series['value'] / $maxRevenue) * 100, $series['value'] > 0 ? 8 : 0) }}%"></div>
                        </div>
                        <p class="text-right text-sm font-bold text-neutral-950">
                            {{ number_format($series['value'], 2) }} DT
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-lg border border-neutral-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex flex-col gap-3 border-b border-neutral-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <p class="text-sm font-semibold text-neutral-500">Recent Orders</p>
                    <h3 class="mt-1 text-xl font-bold text-neutral-950">Latest service tickets</h3>
                </div>
                <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2 text-sm font-bold text-neutral-700 transition hover:bg-neutral-50 hover:text-neutral-950">
                    View all
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead class="bg-neutral-50 text-xs font-bold uppercase tracking-[0.14em] text-neutral-400">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Table Number</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Time</th>
                            <th class="px-6 py-4 text-right">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @forelse($recentOrders as $order)
                            <tr class="transition hover:bg-neutral-50/80">
                                <td class="px-6 py-4 text-sm font-bold text-neutral-950">#{{ $order->id }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-neutral-700">Table {{ optional($order->table)->table_number ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $statusStyles[$order->status] ?? 'bg-neutral-100 text-neutral-700 ring-neutral-200' }} inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold ring-1">
                                        <span class="{{ $statusDots[$order->status] ?? 'bg-neutral-400' }} h-1.5 w-1.5 rounded-full"></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-neutral-950">{{ number_format((float) $order->total_price, 2) }} DT</td>
                                <td class="px-6 py-4 text-sm font-semibold text-neutral-500">{{ $order->created_at->format('H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex h-9 items-center justify-center rounded-lg bg-neutral-950 px-3 text-sm font-bold text-white transition hover:bg-neutral-800">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm font-semibold text-neutral-500">
                                    No orders have been placed yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
            <div>
                <p class="text-sm font-semibold text-neutral-500">Best Selling Items</p>
                <h3 class="mt-1 text-xl font-bold text-neutral-950">Top 5 menu picks</h3>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($bestSellingItems as $index => $sale)
                    @php
                        $menuItem = $sale->menuItem;
                        $rank = ['1', '2', '3', '4', '5'][$index] ?? $index + 1;
                    @endphp
                    <div class="flex items-center gap-4 rounded-lg border border-neutral-100 bg-neutral-50 p-3 transition hover:bg-white hover:shadow-sm">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-sm font-black text-neutral-700 ring-1 ring-neutral-200">
                            {{ $rank }}
                        </span>
                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-neutral-200">
                            @if(optional($menuItem)->image)
                                <img src="{{ asset('storage/' . $menuItem->image) }}" alt="{{ $menuItem->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs font-bold text-neutral-400">
                                    Img
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-neutral-950">{{ optional($menuItem)->name ?? 'Deleted item' }}</p>
                            <p class="text-xs font-semibold text-neutral-500">{{ number_format($sale->orders_count) }} Orders</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-neutral-300 p-6 text-center">
                        <p class="text-sm font-semibold text-neutral-500">Best sellers will appear after orders are placed.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
            <div>
                <p class="text-sm font-semibold text-neutral-500">Menu Overview</p>
                <h3 class="mt-1 text-xl font-bold text-neutral-950">Catalog health</h3>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3">
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-400">Categories</p>
                    <p class="mt-2 text-2xl font-black text-neutral-950">{{ $totalCategories }}</p>
                </div>
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-neutral-400">Menu Items</p>
                    <p class="mt-2 text-2xl font-black text-neutral-950">{{ $menuItemsCount }}</p>
                </div>
                <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-600">Available</p>
                    <p class="mt-2 text-2xl font-black text-emerald-700">{{ $availableItems }}</p>
                </div>
                <div class="rounded-lg border border-rose-100 bg-rose-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-rose-600">Unavailable</p>
                    <p class="mt-2 text-2xl font-black text-rose-700">{{ $unavailableItems }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
            <div>
                <p class="text-sm font-semibold text-neutral-500">Order Activity Timeline</p>
                <h3 class="mt-1 text-xl font-bold text-neutral-950">Latest actions</h3>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($activityOrders as $order)
                    <div class="flex gap-3">
                        <span class="{{ $statusDots[$order->status] ?? 'bg-neutral-400' }} mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full ring-4 ring-neutral-100"></span>
                        <div class="min-w-0 flex-1 border-b border-neutral-100 pb-4">
                            <p class="text-sm font-bold text-neutral-950">
                                Order #{{ $order->id }} {{ $order->status === 'pending' ? 'created' : $order->status }}
                            </p>
                            <p class="mt-1 text-xs font-semibold text-neutral-500">
                                Table {{ optional($order->table)->table_number ?? '-' }} · {{ $order->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-neutral-300 p-6 text-center">
                        <p class="text-sm font-semibold text-neutral-500">No order activity yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-neutral-800 bg-gradient-to-br from-neutral-950 via-neutral-900 to-emerald-950 p-5 text-white shadow-sm sm:p-6">
            <div>
                <p class="text-sm font-semibold text-neutral-400">Quick Actions</p>
                <h3 class="mt-1 text-xl font-bold">Manage faster</h3>
            </div>

            <div class="mt-6 grid gap-3">
                <a href="{{ route('categories.create') }}" class="flex items-center justify-between rounded-lg bg-white/10 px-4 py-4 text-sm font-bold transition hover:-translate-y-0.5 hover:bg-white/15">
                    <span class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-neutral-950">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        Add Category
                    </span>
                </a>

                <a href="{{ route('menu-items.create') }}" class="flex items-center justify-between rounded-lg bg-white/10 px-4 py-4 text-sm font-bold transition hover:-translate-y-0.5 hover:bg-white/15">
                    <span class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-neutral-950">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 3v18M10 3v18M14 4h2a4 4 0 0 1 4 4v13" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        Add Menu Item
                    </span>
                </a>

                <a href="{{ route('tables.create') }}" class="flex items-center justify-between rounded-lg bg-white/10 px-4 py-4 text-sm font-bold transition hover:-translate-y-0.5 hover:bg-white/15">
                    <span class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-neutral-950">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 10h16M6 10v10M18 10v10M7 4h10l3 6H4l3-6Z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        Add Table
                    </span>
                </a>

                <a href="{{ route('orders.index') }}" class="flex items-center justify-between rounded-lg bg-emerald-500 px-4 py-4 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-emerald-400">
                    <span class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-emerald-600">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2h12v20l-3-2-3 2-3-2-3 2V2ZM9 7h6M9 11h6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        View Orders
                    </span>
                </a>
            </div>
        </div>
    </section>
</div>

@endsection
