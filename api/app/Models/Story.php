<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\Viewable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory, Viewable, Commentable;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
