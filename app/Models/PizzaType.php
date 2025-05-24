<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PizzaType extends Model
{
    protected $fillable = ['name', 'ingredients'];

    // A PizzaType has many Pizzas
    public function pizzas()
    {
        return $this->hasMany(Pizza::class);
    }
}
