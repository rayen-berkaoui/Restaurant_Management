<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;

class CartController extends Controller
{
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $item = MenuItem::findOrFail(
            $request->menu_item_id
        );

        $cart = session()->get('cart', []);

        if(isset($cart[$item->id])) {

            $cart[$item->id]['quantity']++;

        } else {

            $cart[$item->id] = [

                'name' => $item->name,

                'price' => $item->price,

                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return back();
    }

    /**
     * Show cart page
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        return view(
            'customer.cart',
            compact('cart')
        );
    }

public function increase($id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {

        $cart[$id]['quantity']++;
    }

    session()->put('cart', $cart);

    return back();
}

public function decrease($id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {

        $cart[$id]['quantity']--;

        if($cart[$id]['quantity'] <= 0) {

            unset($cart[$id]);
        }
    }

    session()->put('cart', $cart);

    return back();
}

public function remove($id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {

        unset($cart[$id]);
    }

    session()->put('cart', $cart);

    return back();
}


public function placeOrder()
{
    $cart = session()->get('cart', []);

    if(empty($cart)) {

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | TEMP TABLE
    |--------------------------------------------------------------------------
    |
    | Later we will dynamically detect table
    | from QR session/token.
    |
    */

    $table = RestaurantTable::first();

    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTAL
    |--------------------------------------------------------------------------
    */

    $total = 0;

    foreach($cart as $item) {

        $total +=
            $item['price'] * $item['quantity'];
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER
    |--------------------------------------------------------------------------
    */

    $order = Order::create([

        'restaurant_id' =>
            $table->restaurant_id,

        'restaurant_table_id' =>
            $table->id,

        'total_price' => $total,

        'status' => 'pending',
    ]);

    /*
    |--------------------------------------------------------------------------
    | CREATE ORDER ITEMS
    |--------------------------------------------------------------------------
    */

    foreach($cart as $menuItemId => $item) {

        OrderItem::create([

            'order_id' => $order->id,

            'menu_item_id' => $menuItemId,

            'quantity' => $item['quantity'],

            'price' => $item['price'],
        ]);
    }

/*
|--------------------------------------------------------------------------
| CLEAR CART
|--------------------------------------------------------------------------
*/

session()->forget('cart');

/*
|--------------------------------------------------------------------------
| REDIRECT CUSTOMER
|--------------------------------------------------------------------------
*/

return redirect()->route(
    'customer.orders.show',
    $order->id
);
}
}
