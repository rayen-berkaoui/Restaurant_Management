<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{
public function index()
{
    $orders = Order::with('table')
        ->latest()
        ->get();

    return view(
        'dashboard.orders.index',
        compact('orders')
    );
}


public function show(string $id)
{
    $order = Order::with([
        'table',
        'items.menuItem'
    ])->findOrFail($id);

    return view(
        'dashboard.orders.show',
        compact('order')
    );
}

public function update(Request $request, string $id)
{
    $order = Order::findOrFail($id);

    $order->update([

        'status' => $request->status,
    ]);

    return back();
}

}