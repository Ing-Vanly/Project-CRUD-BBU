<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
