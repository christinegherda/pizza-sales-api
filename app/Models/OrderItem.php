<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'pizza_id', 'quantity', 'line_total'];

    // An OrderItem belongs to an Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // An OrderItem belongs to a Pizza
    public function pizza()
    {
        return $this->belongsTo(Pizza::class, 'pizza_id', 'pizza_id');
    }
    
}
