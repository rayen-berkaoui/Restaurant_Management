<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;

class RestaurantTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $tables = RestaurantTable::where(
        'restaurant_id',
        1
    )->get();

    return view(
        'dashboard.tables.index',
        compact('tables')
    );
}
    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    return view('dashboard.tables.create');
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    RestaurantTable::create([

        'restaurant_id' => 1,

        'table_number' => $request->table_number,
    ]);

    return redirect()->route('tables.index');
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
    $table = RestaurantTable::findOrFail($id);

    return view(
        'dashboard.tables.edit',
        compact('table')
    );
}
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $table = RestaurantTable::findOrFail($id);

    $table->update([

        'table_number' => $request->table_number,
    ]);

    return redirect()->route('tables.index');
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $table = RestaurantTable::findOrFail($id);

    $table->delete();

    return redirect()->route('tables.index');
}



    public function qr($id)
{
    $table = RestaurantTable::findOrFail($id);

    return view(
        'dashboard.tables.qr',
        compact('table')
    );
}
}
