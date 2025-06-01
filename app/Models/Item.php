<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
   
{
         use HasFactory;
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
public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorite_items');
}

}
