<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // show 'pending' orders first, then 'in process', then 'complete' and 'rejected'
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

        // if status was rejected 'complete' and it is changed, reset confirmation before changing status
        if ($order->status == 'complete' && !is_null($order->confirmation)) {
            $order->confirmation = null;
            $order->update(['status' => $attr['status']]);
            $order->save();
        } // if status is changed to 'rejected', return paid money to buyer
        if ($attr['status'] == 'rejected') {
            if ($order->product->currency == 'stars') {
                $price = $order->product->price;
                $order->buyer->stars += $price;
                $order->buyer->save();
                $order->update(['status' => $attr['status']]);
            }
        } else {
            $order->update(['status' => $attr['status']]);
        }
        return back();
    }

    function confirmComplete(Request $request)
    {
        $order = Order::find($request->order_id);

        // if not auth or trying to confirm someone else's order
        if (!auth()->check() || $order->buyer->id !== auth()->user()->id)
            return back()->with('error', 'You can\'t do that.');

        $order->confirmation = true;

        // if order is one-time, set the product inactive
        $order->product->active = false;
        $order->product->save();

        // if order is confirmed as complete, release payment to seller
        // if order was in stars, give the price value
        if ($order->product->currency == 'stars') {
            $order->seller->stars += $order->product->price;
            $order->seller->save();
        } // if order was in euro, give seller 100 stars as a reward
        else {
            $reward = 100;
            $order->seller->stars += $reward;
            $order->seller->save();
        }
        $order->save();

        return back();
    }

    function rejectComplete(Request $request)
    {
        $order = Order::find($request->order_id);

        // if not auth or trying to reject someone else's order
        if (!auth()->check() || $order->buyer->id !== auth()->user()->id)
            return back()->with('error', 'You can\'t do that.');

        $order->confirmation = false;
        $order->save();

        return back();
    }
}
