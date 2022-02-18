<?php

namespace App\Traits;

use App\Models\Like;
use App\Models\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Viewable
{
  public function scopeWithSeen(Builder $query, $user)
  {
    $query->leftJoinSub(
      "select id seen_id, story_id, user_id v_user_id from views where views.user_id = $user and views.user_id is not null",
      'views',
      'views.story_id',
      'stories.id'
    );
  }

  public function viewers()
  {
    return $this->hasMany(View::class);
  }

  public function view($user)
  {
    return $this->viewers()->updateOrCreate([
      'user_id' => $user->id
    ]);
  }

  public function isViewedBy($user)
  {
    if ($user == null)
      return false;

    return (bool) $user->seenStories()
      ->where('story_id', $this->id)
      ->count();
  }
}
