<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable= ['name', 'description', 'image', 'main_category', 'price'];
    protected $table= 'items';

     public function reviews()
{
    return $this->hasMany(Review::class);
}

public function category()
{
    return $this->belongsTo(Category::class);
}
}
