<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RestaurantTable extends Model
{
    protected $fillable = [
        'restaurant_id',
        'table_number',
        'qr_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($table) {

            $table->qr_token = Str::random(10);
        });
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}