<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\RestaurantTable;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.home', [

            'ordersCount' =>
                Order::count(),

            'revenue' =>
                Order::where('status', 'completed')
                    ->sum('total_price'),

            'menuItemsCount' =>
                MenuItem::count(),

            'tablesCount' =>
                RestaurantTable::count(),

            'pendingOrders' =>
                Order::where('status', 'pending')->count(),

            'preparingOrders' =>
                Order::where('status', 'preparing')->count(),

            'completedOrders' =>
                Order::where('status', 'completed')->count(),

            'cancelledOrders' =>
                Order::where('status', 'cancelled')->count(),

            'recentOrders' =>
                Order::latest()
                    ->take(5)
                    ->get(),
        ]);
    }
}