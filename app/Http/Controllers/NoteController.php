<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        // $request['body'] = $request['notebody'];
        // dd($request);

        $attributes = $request->validate([
            'notebody' => 'max:600|required',
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = time();

        Note::create($attributes);

        return back()
            ->with('success', 'You have successfully created a note!');
    }

    public function destroy(Request $request)
    {
        $note = Note::find($request->id);
        if (Auth::user()->id === $note->author->id) {
            $note->delete();
            return redirect('/profile');
        } else {
            return redirect()->route('explore');
        }
    }
}
