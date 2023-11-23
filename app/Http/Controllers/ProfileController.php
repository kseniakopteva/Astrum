<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AboutLink;
use App\Models\Badge;
use App\Models\Note;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(User $author)
    {
        return view('profile.index', [
            'user' => $author,
            'posts' => Post::where('removed', false)->latest()->whereHas('author', fn ($q) => $q->where('user_id', $author->id))->paginate(20),
            'notes' => Note::where('removed', false)->latest()->where('removed', '=', 0)->whereHas('author', fn ($q) => $q->where('user_id', $author->id))->get()->take(20),
            'followers' => $author->followers,
            'following' => $author->following,
        ]);
    }

    public function posts(User $author)
    {
        return view('profile.posts', [
            'user' => $author,
            'posts' =>  Post::where('removed', false)->latest()->whereHas('author', fn ($q) => $q->where('user_id', $author->id))->paginate(20)->withQueryString(),
            'followers' => $author->followers,
            'following' => $author->following,
        ]);
    }

    public function notes(User $author)
    {
        return view('profile.notes', [
            'user' => $author,
            'notes' =>  Note::where('removed', false)->latest()->where('removed', '=', 0)->whereHas('author', fn ($q) => $q->where('user_id', $author->id))->paginate(20)->withQueryString(),
            'followers' => $author->followers,
            'following' => $author->following,
        ]);
    }

    public function shop(User $author)
    {
        return view('profile.shop', [
            'user' => $author,
            'followers' => $author->followers,
            'following' => $author->following,
            'products' => Product::where('user_id', $author->id)
                // ->orderBy('currency')
                ->orderBy('price', 'ASC')->paginate(12) //->get()
        ]);
    }

    public function faq(User $author)
    {
        return view('profile.faq', [
            'user' => $author,
            'followers' => $author->followers,
            'following' => $author->following,
        ]);
    }

    public function about(User $author)
    {
        return view('profile.about', [
            'user' => $author,
            'followers' => $author->followers,
            'following' => $author->following,
        ]);
    }

    public function about_store_link(Request $request)
    {
        if (!auth()->check())
            return redirect()->back()->with('error', 'You need to be authorized!');

        $attr = $request->validate([
            'name' => 'required|max:255',
            'link' => 'required|url'
        ]);

        $attr['user_id'] = auth()->user()->id;

        AboutLink::create($attr);

        return redirect()->back()->with('success', 'Link created!');
    }

    public function about_destroy_link(Request $request)
    {
        if (!auth()->check())
            return redirect()->back()->with('error', 'You need to be authorized!');

        AboutLink::find($request->link_id)->delete();

        return redirect()->back()->with('success', 'Link removed!');
    }

    public function about_update(Request $request)
    {
        if (!auth()->check())
            return redirect()->back()->with('error', 'You need to be authorized!');

        $attr = $request->validate([
            'about' => 'max:4000'
        ]);

        $user = auth()->user();

        User::find($user->id)->update(['about' => $attr['about']]);
        return redirect()->back()->with('success', 'You have updated your \'About\' section!');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'badges' => Badge::all()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        if ($request->image && $request->image->isValid() && fnmatch(auth()->user()->image, 'default[1234567].png')) {
            File::delete(public_path('images') . '/' . auth()->user()->image);
        }

        $request->user()->fill($request->validated());

        $user =  User::find(Auth::id());
        if (!$user) {
            return response()->json(['message' => 'No User Found'], 404);
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->image && $request->image->isValid()) {

            $imageName = strtolower($request->user()->username) . '_profile' . '.' . $request->image->extension();
            // $image = $request->file('image');
            $filePath = public_path('images/profile-pictures');

            $request->user()->image = $imageName;
            $request->image->move($filePath, $imageName);

            Image::make($filePath . '/' . $imageName)->fit(500)->save($filePath . '/' . $imageName);

            // $image->move($filePath, $imageName);
        }

        // if ($request->image && $request->image->isValid()) {
        //     $imageName = strtolower($request->user()->username) . '_profile' . '.' . $request->image->extension();
        //     $request->user()->image = $imageName;
        //     $request->image->move(public_path('images'), $imageName);
        // }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', 'You have updated your profile!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function removeImage()
    {
        $user =  User::find(Auth::id());
        if (!fnmatch('default[1234567].png', $user->image))
            File::delete(public_path('images') . '/' . $user->image);

        $user->image = 'default' . rand(1, 7) . '.png';
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function setCurrentWallpaper(Request $request)
    {
        if (!auth()->check())
            return redirect('/explore');

        $user = User::where('id', auth()->user()->id)->first();
        $user->wallpaper_id = $request->id;
        $user->save();

        return redirect('/settings#customise')->with('success', 'You have changed the current wallpaper!');
    }

    public function setCurrentProfilePictureFrame(Request $request)
    {
        if (!auth()->check())
            return redirect('/explore');

        $user = User::where('id', auth()->user()->id)->first();
        $user->profile_picture_frame_id = $request->id;
        $user->save();

        return redirect('/settings#customise')->with('success', 'You have changed the current profile picture frame!');
    }

    public function setCurrentColour(Request $request)
    {
        if (!auth()->check())
            return redirect('/explore');

        $user = User::where('id', auth()->user()->id)->first();
        $user->colour_id = $request->id;
        $user->save();

        return redirect('/settings#customise')->with('success', 'You have changed the current colour!');
    }
}
