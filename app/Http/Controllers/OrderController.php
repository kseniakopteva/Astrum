<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => Order::where('seller_id', auth()->user()->id)->orderByRaw('CASE WHEN status = "pending" THEN 1 WHEN status = "working" THEN 2 WHEN status = "complete" THEN 3 ELSE 4 END')
                ->orderBy('created_at', 'DESC')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $attr = $request->validate([
            'status' => 'required'
        ]);

        $order = Order::find($request->order_id);
        $order->update(['status' => $attr['status']]);

        return back();
    }
}
