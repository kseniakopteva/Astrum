<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Badge;
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
        if ($request->image && $request->image->isValid() && !fnmatch(auth()->user()->image, 'default*.png')) {
            File::delete(public_path('images') . '\\' . auth()->user()->image);
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
            $image = $request->file('image');
            $filePath = public_path('images');

            $request->user()->image = $imageName;
            $request->image->move(public_path('images'), $imageName);

            $img = Image::make(public_path('images') . '\\' . $imageName)->fit(500)->save($filePath . '\\' . $imageName);

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
        // dd($user->image, !fnmatch($user->image, 'default[12345].png'));
        if (fnmatch($user->image, 'default[12345].png'))
            File::delete(public_path('images') . '\\' . $user->image);

        $user->image = 'default' . rand(1, 7) . '.png';
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}