<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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



    public function follow(User $user)
    {
        if (!$this->isFollowing($user)) {
            Follow::create([
                'user_id' => auth()->id(),
                'following_id' => $user->id
            ]);
        }
    }

    public function unfollow(User $user)
    {
        Follow::where('user_id', auth()->id())->where('following_id', $user->id)->delete();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('users.id', $user->id)->exists();
    }


    public function following()
    {
        return $this->hasManyThrough(User::class, Follow::class, 'user_id', 'id', 'id', 'following_id');
    }

    public function followers()
    {
        return $this->hasManyThrough(User::class, Follow::class, 'following_id', 'id', 'id', 'user_id');
    }
}
