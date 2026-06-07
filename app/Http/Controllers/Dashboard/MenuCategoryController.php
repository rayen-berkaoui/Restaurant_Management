<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuCategory;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $categories = MenuCategory::where(
        'restaurant_id',1
    )->get();

    return view('dashboard.categories.index', compact('categories'));
}
    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    return view('dashboard.categories.create');
}
    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    MenuCategory::create([

        'restaurant_id' => 1,

        'name' => $request->name,
    ]);

    return redirect()->route('categories.index');
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
    $category = MenuCategory::findOrFail($id);

    return view(
        'dashboard.categories.edit',
        compact('category')
    );
}
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $category = MenuCategory::findOrFail($id);

    $category->update([

        'name' => $request->name,
    ]);

    return redirect()->route('categories.index');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $category = MenuCategory::findOrFail($id);

    $category->delete();

    return redirect()->route('categories.index');
}
}
