<?php

namespace App\Traits;

use App\Models\Like;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait Likeable
{
  public function scopeWithLikes(Builder $query)
  {
    $query->leftJoinSub(
      'select tweet_id, ifnull(sum(liked), 0) likes, ifnull(sum(not liked), 0) dislikes from likes group by tweet_id',
      'likes',
      'likes.tweet_id',
      'tweets.id'
    );
  }

  public function scopeWithIsLiked(Builder $query, $userId)
  {
    $query->leftJoinSub(
      "select tweet_id, count(*) is_liked from likes where user_id = $userId and liked = 1 group by tweet_id",
      'is_liked_by',
      'is_liked_by.tweet_id',
      'tweets.id'
    );
  }

  public function scopeWithIsDisliked(Builder $query, $userId)
  {
    $query->leftJoinSub(
      "select tweet_id, count(*) is_disliked from likes where user_id = $userId and liked = 0 group by tweet_id",
      'dis_liked_by',
      'dis_liked_by.tweet_id',
      'tweets.id'
    );
  }

  public function likes()
  {
    return $this->hasMany(Like::class);
  }

  public function like($user = null, $liked = true)
  {
    $this->likes()->updateOrCreate([
      'user_id' => $user ? $user->id : Auth::user()->id
    ], [
      'liked' => $liked
    ]);
  }

  public function unlike($user = null)
  {
    $this->likes()->where('user_id', $user->id)->delete();
  }

  public function dislike($user = null)
  {
    $this->like($user, false);
  }

  public function isLikedBy($user, $liked = true)
  {
    if ($user == null)
      return false;

    return (bool) $user->likes
      ->where('tweet_id', $this->id)
      ->where('liked', $liked)
      ->count();
  }

  public function isDislikedBy($user)
  {
    if ($user == null)
      return false;

    return $this->isLikedBy($user, false);
  }
}
