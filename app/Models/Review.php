<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable= ['user_id', 'item_id', 'rating', 'review'];
    protected $table= 'reviews';

    public function user()
{
    return $this->belongsTo(User::class);
}

public function item()
{
    return $this->belongsTo(Item::class);
}
}