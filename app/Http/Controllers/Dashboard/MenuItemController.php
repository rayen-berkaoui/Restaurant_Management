<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $items = MenuItem::where(
        'restaurant_id',
        1
    )->get();

    return view(
        'dashboard.menu-items.index',
        compact('items')
    );
}
    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $categories = MenuCategory::where(
        'restaurant_id',
        1
    )->get();

    return view(
        'dashboard.menu-items.create',
        compact('categories')
    );
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $imagePath = null;

if($request->hasFile('image')) {

    $imagePath = $request->file('image')
        ->store('menu-items', 'public');
}
    MenuItem::create([

        'restaurant_id' => 1,

        'menu_category_id' => $request->menu_category_id,

        'name' => $request->name,

        'description' => $request->description,

        'price' => $request->price,

        'available' => $request->has('available'),

        'image' => $imagePath,
    ]);

    return redirect()->route('menu-items.index');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(string $id)
{
    $item = MenuItem::findOrFail($id);

    $categories = MenuCategory::where(
        'restaurant_id',
        1
    )->get();

    return view(
        'dashboard.menu-items.edit',
        compact('item', 'categories')
    );
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $item = MenuItem::findOrFail($id);

    $imagePath = $item->image;

    if($request->hasFile('image')) {

        $imagePath = $request->file('image')
            ->store('menu-items', 'public');
    }

    $item->update([

        'menu_category_id' =>
            $request->menu_category_id,

        'name' => $request->name,

        'description' =>
            $request->description,

        'price' => $request->price,

        'image' => $imagePath,

        'available' =>
            $request->has('available'),
    ]);

    return redirect()
        ->route('menu-items.index');
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $item = MenuItem::findOrFail($id);

    $item->delete();

    return redirect()->route('menu-items.index');
}
}
