<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteLike;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function show(User $author, Note $note)
    {
        return view('notes.show', [
            'note' => $note,
            'ancestors' => $note->ancestors->reverse(),
            'user' => $author
        ]);
    }

    public function store(Request $request, Note $note)
    {
        if (auth()->user()->isBanned())
            return back()->with('error', 'You can\'t write notes because you are banned.');

        $attributes = $request->validate([
            'notebody' => 'max:600|required',
        ]);

        // checking if note has a parent note
        if (!is_null($note)) {
            $attributes['parent_id'] = $note->id;
        } else {
            $attributes['parent_id'] = NULL;
        }

        $attributes['user_id'] = auth()->user()->id;
        $attributes['slug'] = time();

        // price to post a note is 5 stars
        $price = 5;
        $u = auth()->user();
        if ($u->stars < $price)
            return back()->with('error', 'You don\'t have enough money!');

        $new_note = Note::create($attributes);
        $u->stars -= $price;
        $u->save();

        // creating or using existing tags, syncing
        $tags = array_filter(array_map('trim', explode(',', $request['tags'])));
        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag], ['slug' => str_replace(' ', '_', $tag)])->save();
        }
        $tags = Tag::whereIn('name', $tags)->get()->pluck('id');
        $new_note->tags()->sync($tags);

        return redirect()->route('note.show', ['author' => auth()->user()->username, 'note' => $new_note->slug])
            ->with('success', 'You have successfully created a note!');
    }

    public function destroy(Request $request)
    {
        $note = Note::find($request->id);
        if (Auth::user()->id === $note->author->id) {
            // notes are not fully removed to show 'Note deleted' in the thread if note has children
            $note->update(['removed' => 1]);
            return redirect('/profile');
        } else {
            return redirect()->route('explore');
        }
    }

    public function toggleLike(Note $note)
    {
        // if user has never liked the note, create new db row
        if (!NoteLike::where('user_id', auth()->id())->where('note_id', $note->id)->exists()) {
            NoteLike::create([
                'user_id' => auth()->user()->id,
                'note_id' => $note->id,
                'liked' => 1
            ]);
            // if it is not this user's note, give money (1 star) to the user
            if ($note->author->id !== auth()->user()->id) {
                $note->author->stars++;
                $note->author->save();
            }
        } // if user has liked this note before (record exists)
        else {
            $noteLike = NoteLike::where('user_id', auth()->id())->where('note_id', $note->id);
            // check if the existing record says the note is liked or not, toggle it
            $isLiked = $note->isLiked($note);
            if (!$isLiked) {
                $noteLike->update(['liked' => 1]);
            } else {
                $noteLike->update(['liked' => 0]);
            }
        }
        return back();
    }
}
