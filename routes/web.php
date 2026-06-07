<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\MenuCategoryController;
use App\Http\Controllers\Dashboard\MenuItemController;
use App\Http\Controllers\Dashboard\RestaurantTableController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;




Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->name('dashboard');
Route::resource('categories', MenuCategoryController::class);
Route::resource('menu-items', MenuItemController::class);
Route::resource('tables', RestaurantTableController::class);
Route::resource('orders', OrderController::class);
Route::get(
    '/customer/orders/{order}',
    [CustomerOrderController::class, 'show']
)->name('customer.orders.show');

Route::post('/cart/increase/{id}', [CartController::class, 'increase'])
    ->name('cart.increase');

Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])
    ->name('cart.decrease');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])
    ->name('cart.remove');    


Route::get('/menu/{token}', [CustomerMenuController::class, 'show']);

Route::get('/', function () {
    return view('welcome');
});


Route::get(
    'tables/{table}/qr',
    [RestaurantTableController::class, 'qr']
)->name('tables.qr');

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/add', [CartController::class, 'add'])
    ->name('cart.add');

Route::post(
    '/orders/place',
    [CartController::class, 'placeOrder']
)->name('orders.place');