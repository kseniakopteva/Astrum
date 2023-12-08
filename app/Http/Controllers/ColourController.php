<?php

namespace App\Http\Controllers;

use App\Models\Colour;
use Illuminate\Http\Request;

class ColourController extends Controller
{
    public function index()
    {
        return view('starshop.colours.index', [
            'colours' => Colour::all()
        ]);
    }

    public function buy(Request $request)
    {
        if (!auth()->check())
            return back();

        $colour = Colour::find($request->id);

        // buy only if user has enough money
        if (auth()->user()->stars >= $colour->price) {
            auth()->user()->ownedColours()->attach([
                'colour_id' => $colour->id,
                'user_id' => auth()->user()->id
            ]);

            auth()->user()->stars -= $colour->price;
            auth()->user()->save();
            return back()
                ->with('success', 'You have successfully purchased a colour!');
        }
        return back()
            ->with('error', 'You don\'t have enough money!');
    }
}
