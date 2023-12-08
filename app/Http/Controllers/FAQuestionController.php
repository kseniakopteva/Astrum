<?php

namespace App\Http\Controllers;

use App\Models\FAQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FAQuestionController extends Controller
{
    public function store(Request $request)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t create FAQ because you are banned.');

        $attributes = $request->validate([
            'question' => 'required|max:256',
            'answer' => 'required|max:4000'
        ]);

        $attributes['user_id'] = auth()->user()->id;

        FAQuestion::create($attributes);

        return back()
            ->with('success', 'You have successfully created a FAQ!');
    }
    public function destroy(Request $request)
    {
        $faq = FAQuestion::find($request->faq_id);

        if (Auth::user()->id === $faq->author->id) {
            $faq->delete();
            return redirect('/profile/' . $faq->author->username . '/faq')->with('success', 'You have deleted the FAQ!');
        } else {
            return redirect()->route('explore');
        }
    }
}
