<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderLookupController extends Controller
{
    public function show(Request $request)
    {
        $order = null;

        if ($request->filled('order_number') && $request->filled('phone')) {
            $order = Order::where('order_number', $request->order_number)
                ->where('customer_phone', $request->phone)
                ->with('items')
                ->first();
        }

        return view('order.lookup', compact('order'));
    }
}
