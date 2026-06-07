<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'primary_color',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class);
    }

    public function categories()
    {
        return $this->hasMany(MenuCategory::class);
    }
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}