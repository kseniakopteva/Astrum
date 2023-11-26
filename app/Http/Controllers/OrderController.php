<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => Order::where('seller_id', auth()->user()->id)->orderByRaw('CASE WHEN status = "pending" THEN 1 WHEN status = "in_process" THEN 2 WHEN status = "complete" THEN 3 ELSE 4 END')
                ->orderBy('created_at', 'DESC')->get(),
            'my_orders' => Order::where('buyer_id', auth()->user()->id)->orderByRaw('CASE WHEN status = "pending" THEN 1 WHEN status = "in_process" THEN 2 WHEN status = "complete" THEN 3 ELSE 4 END')
                ->orderBy('created_at', 'DESC')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $attr = $request->validate([
            'status' => 'required'
        ]);

        $order = Order::find($request->order_id);

        if ($order->status == 'complete' && !is_null($order->confirmation)) {
            $order->confirmation = null;
            $order->update(['status' => $attr['status']]);
            $order->save();
        } else {

            $order->update(['status' => $attr['status']]);
        }
        return back();
    }

    function confirmComplete(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!auth()->check() || $order->buyer->id !== auth()->user()->id)
            return back()->with('error', 'You can\'t do that.');

        $order->confirmation = true;
        $order->save();

        return back();
    }

    function rejectComplete(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!auth()->check() || $order->buyer->id !== auth()->user()->id)
            return back()->with('error', 'You can\'t do that.');

        $order->confirmation = false;
        $order->save();

        return back();
    }
}
