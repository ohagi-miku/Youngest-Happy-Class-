<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    #A post belongs to a user
    #To get the owner of the post
    #1-many (inverse)
    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }


    # To get the categories under a post
    public function categoryPost() {
        return $this->hasMany(CategoryPost::class);
    }

    # 1-many To get all the comments under a post
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    # To get the Likes of the post

    public function likes() {
        return $this->hasMany(Like::class);
    }

    # Return TRUE if the Auth User already liked the post
    public function isLiked() {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
        // $this->likes refersto the likes of a post(get all the likes) And from that result, we are going to search fonthe user id of the AUTH USER if it exists.
    }
}
