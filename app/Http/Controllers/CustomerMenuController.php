<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;

class CustomerMenuController extends Controller
{
    public function show($token)
    {
        $table = RestaurantTable::where(
            'qr_token',
            $token
        )->firstOrFail();

        $restaurant = $table->restaurant;

        $categories = $restaurant
            ->categories()
            ->with('items')
            ->get();

        return view(
            'customer.menu',
            compact(
                'restaurant',
                'table',
                'categories'
            )
        );
    }
}