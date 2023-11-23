<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostFrame;
use App\Models\ProfilePictureFrame;
use App\Models\Report;
use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index($type)
    {
        return view('reports.index', [
            'type' => $type,
            'reports' => Report::where('reported_type', $type)->orderBy('resolved', 'ASC')->orderBy('created_at', 'DESC')->get(),
        ]);
    }

    public function store(Request $request, $type)
    {
        if (!auth()->check())
            return back()->with('error', 'You need to be authorized to do this.');

        $attributes = $request->validate([
            'reported_id' => 'required',
            'reason' => 'required|max:1000'
        ]);

        $attributes['user_id'] = auth()->user()->id;
        $attributes['reported_type'] = $type;

        Report::create($attributes);
        return true;
    }

    public function store_post(Request $request)
    {
        if ($this->store($request, 'post')) {

            $post = Post::find($request['reported_id']);
            return $this->redir('post.show', ['post' => $post->slug, 'author' => $post->author->username]);
        }
    }
    public function store_comment(Request $request)
    {
        if ($this->store($request, 'post-comment')) {

            $comment = PostComment::find($request['reported_id']);
            return $this->redir('post.show', ['post' => $comment->post->slug, 'author' => $comment->post->author->username]);
        }
    }
    public function store_note(Request $request)
    {
        if ($this->store($request, 'note')) {

            $note = Note::find($request['reported_id']);
            return $this->redir('note.show', ['note' => $note->slug, 'author' => $note->author->username]);
        }
    }
    public function store_user(Request $request)
    {
        if ($this->store($request, 'user')) {

            $user = User::find($request['reported_id']);
            return $this->redir('profile.index', ['author' => $user->username]);
        }
    }
    public function store_starshop(Request $request)
    {
        if ($request->type == 'wallpaper') {
            if ($this->store($request, 'wallpaper')) {

                $wallpaper = Wallpaper::find($request['reported_id']);
                return $this->redir('starshop.wallpapers.show', ['wallpaper' => $wallpaper->slug]);
            }
        } elseif ($request->type == 'profile-picture-frame') {
            if ($this->store($request, 'profile-picture-frame')) {

                $profile_picture_frame = ProfilePictureFrame::find($request['reported_id']);
                return $this->redir('starshop.profile-picture-frames.show', ['profile_picture_frame' => $profile_picture_frame->slug]);
            }
        } elseif ($request->type == 'post-frame') {
            if ($this->store($request, 'post-frame')) {

                $post_frame = PostFrame::find($request['reported_id']);
                return $this->redir('starshop.post-frames.show', ['post_frame' => $post_frame->slug]);
            }
        }
    }

    public function redir($route, $attr)
    {
        return redirect()->route($route, $attr)->with('success', 'Report submitted. Our moderators will look at it ASAP.');
    }

    public function destroy(Request $request)
    {
        $report = Report::find($request->report_id);

        $type = $request->type;
        $class = '\App\Models\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $type)));
        $reported = $class::find($report->reported_id);

        if (auth()->user()->isModOrMore()) {
            if (is_null($reported)) {
                $report->delete();
            } else {
                $report->resolved = true;
                $report->save();
            }
            return back();
        } else {
            return redirect()->route('explore');
        }
    }

    public function approve(Request $request)
    {
        $report = Report::find($request->report_id);
        $class = $request->class;
        $item = $class::find($request->reported_id);
        if (auth()->user()->isModOrMore()) {
            $item->removed = true;
            $item->save();
            $report->resolved = true;
            $report->save();
            return back();
        } else {
            return redirect()->route('explore');
        }
    }
}
