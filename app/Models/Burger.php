<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Burger extends Model
{
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'burger_cart', 'burger_id', 'cart_id')->withPivot('quantity');
    }
}
