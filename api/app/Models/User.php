<?php

namespace App\Models;

use App\Traits\Followable;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getAvatarAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }

        return asset('images/default-avatar.jpeg');
    }

    public function timeline($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): EloquentBuilder
    {
        $user = User::where('username', $args['username'])->first();

        $friends = $user->follows()->pluck('id');

        return Tweet::query()
            ->whereIn('user_id', $friends)
            ->orWhere('user_id', $user->id)
            ->withLikes()
            ->withIsLiked($args['auth_user_id'])
            ->withIsDisliked($args['auth_user_id'])
            ->orderByDesc('id');
    }

    public function path($append = '')
    {
        $path = route('profile', $this->username);

        return $append ? "{$path}/{$append}" : $path;
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function seenStories()
    {
        return $this->hasMany(StoryViews::class);
    }
}
