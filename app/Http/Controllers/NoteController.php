<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        // $request['body'] = $request['notebody'];
        // dd($request);

        $attributes = $request->validate([
            'notebody' => 'max:600',
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = time();

        Note::create($attributes);

        return back()
            ->with('success', 'You have successfully created a note!');
    }
}
