<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Pizza; 

class SalesController extends Controller
{
    public function orders(Request $request)
    {
        return Order::with('items')->get();
    }

    public function pizzas()
    {
        return Pizza::with('type')->get();
    }

    public function stats()
    {
        return response()->json([
            'total_orders' => Order::count(), 
            'top_pizza' => Pizza::withCount('orderItems')
                                ->orderByDesc('order_items_count')
                                ->first(),
        ]);
    }
}
