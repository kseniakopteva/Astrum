<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteCommentController extends Controller
{
    public function store(Note $note)
    {
        request()->validate([
            'body' => 'required|max:4000'
        ]);

        $note->comments()->create([
            'user_id' => request()->user()->id,
            'body' => request()->input('body')
        ]);

        return back();
    }
}
