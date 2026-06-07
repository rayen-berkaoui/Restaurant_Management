<?php

namespace App\Http\Controllers;

use App\Models\Order;

class CustomerOrderController extends Controller
{
    public function show(Order $order)
    {
        $order->load('items.menuItem');

        return view(
            'customer.order-status',
            compact('order')
        );
    }
}