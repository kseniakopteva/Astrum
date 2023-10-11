<?php

namespace App\Http\Controllers;

use App\Models\FAQuestion;
use Illuminate\Http\Request;

class FAQuestionController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'question' => 'required|max:256',
            'answer' => 'required|max:4000'
        ]);

        $attributes['user_id'] = auth()->user()->id;

        FAQuestion::create($attributes);

        return back()
            ->with('success', 'You have successfully created a FAQ!');
    }
}
