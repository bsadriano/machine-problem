<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Commentable
{
  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function comment($user, $body)
  {
    $this->comments()->updateOrCreate([
      'user_id' => $user->id,
      'body' => $body
    ]);
  }
}
