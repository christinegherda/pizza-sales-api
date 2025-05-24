<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    protected $fillable = ['pizza_id', 'pizza_type_id', 'size', 'price'];

    protected $primaryKey = 'pizza_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // A Pizza belongs to a PizzaType
    public function type()
    {
        return $this->belongsTo(PizzaType::class, 'pizza_type_id', 'pizza_type_id');
    }

    // A Pizza has many OrderItems  
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'pizza_id', 'pizza_id');
    }

}
