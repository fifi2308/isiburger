<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
    
    protected $fillable = ['user_id'];  // Assure-toi que user_id est bien dans le tableau des colonnes fillables

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // Modèle Cart
    public function burgers()
    {
        return $this->belongsToMany(Burger::class, 'burger_cart', 'cart_id', 'burger_id')->withPivot('quantity');
    }

}
