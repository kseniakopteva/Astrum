<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function colour()
    {
        return $this->belongsTo(Colour::class);
    }

    public function profilePictureFrames()
    {
        return $this->belongsToMany(ProfilePictureFrame::class);
    }

    public function questions()
    {
        return $this->hasMany(FAQuestion::class);
    }

    public function postLikes()
    {
        return $this->belongsToMany(Post::class, 'post_id');
    }

    /* -------------------------------------------------------------------------- */
    /*                                 WALLPAPERS                                 */
    /* -------------------------------------------------------------------------- */

    public function ownedWallpapers()
    {
        return $this->belongsToMany(Wallpaper::class, 'user_wallpaper');
    }
    public function createdWallpapers()
    {
        return $this->hasMany(Wallpaper::class, 'wallpaper_id');
    }
    public function currentWallpaper()
    {
        return $this->belongsTo(Wallpaper::class, 'wallpaper_id');
    }
    public function hasWallpaper($id)
    {
        return $this->ownedWallpapers()->where('wallpaper_id', $id)->exists();
    }

    /* -------------------------------------------------------------------------- */
    /*                           PROFILE PICTURE FRAMES                           */
    /* -------------------------------------------------------------------------- */

    public function ownedProfilePictureFrames()
    {
        return $this->belongsToMany(ProfilePictureFrame::class, 'user_profile_picture_frame');
    }
    public function createdProfilePictureFrames()
    {
        return $this->hasMany(ProfilePictureFrame::class, 'profile_picture_frame_id');
    }
    public function currentProfilePictureFrame()
    {
        return $this->belongsTo(ProfilePictureFrame::class, 'profile_picture_frame_id');
    }
    public function hasProfilePictureFrame($id)
    {
        return $this->ownedProfilePictureFrames()->where('profile_picture_frame_id', $id)->exists();
    }


    public function ownedColours()
    {
        return $this->belongsToMany(Colour::class, 'colour_user');
    }
    public function hasColour($id)
    {
        return $this->ownedColours()->where('colour_id', $id)->exists();
    }


    public function ownedPostFrames()
    {
        return $this->belongsToMany(PostFrame::class, 'post_frame_user')->withPivot(['amount']);
    }
    public function postFrameAmount($id)
    {
        if ($this->ownedPostFrames()->where('post_frame_id', $id)->exists())
            return $this->ownedPostFrames()->where('post_frame_id', $id)->first()->pivot->amount;
        else
            return null;
    }


    public function hasItem($id, $type)
    {
        switch ($type) {
            case 'wallpaper':
                return $this->ownedWallpapers()->where('wallpaper_id', $id)->exists();
                break;
            case 'profile-picture-frame':
                return $this->ownedProfilePictureFrames()->where('profile_picture_frame_id', $id)->exists();
                break;
            default:
                return null;
                break;
        }
    }

    public function follow(User $user)
    {
        if (!Follow::where('user_id', auth()->id())->where('following_id', $user->id)->exists()) {
            Follow::create([
                'user_id' => auth()->id(),
                'following_id' => $user->id,
                'is_currently_following' => true
            ]);
            $user->stars += 10;
            $user->save();
        } else {
            Follow::where('user_id', auth()->id())->where('following_id', $user->id)->update(['is_currently_following' => true]);
        }
    }

    public function unfollow(User $user)
    {
        Follow::where('user_id', auth()->id())->where('following_id', $user->id)->update(['is_currently_following' => false]);
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('users.id', $user->id)->where('is_currently_following', '=', 1)->exists();
    }


    public function following()
    {
        return $this->hasManyThrough(User::class, Follow::class, 'user_id', 'id', 'id', 'following_id')->where('is_currently_following', '=', 1);
    }

    public function followers()
    {
        return $this->hasManyThrough(User::class, Follow::class, 'following_id', 'id', 'id', 'user_id')->where('is_currently_following', '=', 1);
    }

    public function isCreator()
    {
        return $this->role === 'creator';
    }

    public function isCreatorOrMore()
    {
        return in_array($this->role, ['creator', 'mod', 'admin']);
    }

    public function isMod()
    {
        return $this->role === 'mod';
    }

    public function isModOrMore()
    {
        return in_array($this->role, ['mod', 'admin']);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function reports()
    {
        return Report::where('reported_type', 'user')->where('reported_id', $this->id)->latest()->get();
    }

    public function isBanned()
    {
        return Ban::where('user_id', $this->id)
            ->where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where(function ($query) {
                $query->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
                    ->orWhereNull('end_date');
            })
            ->exists();
    }

    public function getCurrentBan()
    {
        return Ban::where('user_id', $this->id)
            ->where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where(function ($query) {
                $query->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
                    ->orWhereNull('end_date');
            })
            ->first();
    }

    static public function getBannedUserIds()
    {
        return Ban::where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where(function ($query) {
                $query->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
                    ->orWhereNull('end_date');
            })
            ->pluck('user_id');
    }

    static public function getBannedUsers()
    {
        return Ban::where('start_date', '<', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
            ->where(function ($query) {
                $query->where('end_date', '>', Carbon::now()->timezone('Europe/Riga')->toDateTimeString())
                    ->orWhereNull('end_date');
            })
            ->get();
    }

    public function isBlockedBy(User $user)
    {
        return Block::where('user_id', $user->id)->where('blocked_id', $this->id)->exists();
    }

    public function allBlockedBy()
    {
        return Block::where('blocked_id', $this->id)->pluck('user_id')->all();
    }
}
