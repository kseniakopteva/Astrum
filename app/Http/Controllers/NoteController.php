<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function show(User $author, Note $note)
    {
        return view('note', [
            'note' => $note,
            'ancestors' => $note->ancestors->reverse(),
            'user' => $author
        ]);
    }

    public function store(Request $request, Note $note)
    {
        $attributes = $request->validate([
            'notebody' => 'max:600|required',
        ]);

        if (!is_null($note)) {
            $attributes['parent_id'] = $note->id;
        } else {
            $attributes['parent_id'] = NULL;
        }

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = time();

        $u = auth()->user();
        $price = 5;
        if ($u->stars < $price)
            return back()->with('success', 'Not enough stars!');

        $new_note = Note::create($attributes);
        $u->stars -= $price;
        $u->save();

        return redirect()->route('note.show', ['author' => auth()->user()->username, 'note' => $new_note->slug])
            ->with('success', 'You have successfully created a note!');
    }

    public function destroy(Request $request)
    {
        $note = Note::find($request->id);
        if (Auth::user()->id === $note->author->id) {
            $note->update(['removed' => 1]);
            return redirect('/profile');
        } else {
            return redirect()->route('explore');
        }
    }
}
