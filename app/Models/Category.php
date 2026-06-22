<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //ADMIN SIDE - get all posts under a category
    public function categoryPost() {
        return $this->hasMany(CategoryPost::class);
    }

    // SEARCH - カテゴリーに紐づく投稿を取得する
    public function posts() {
        return $this->belongsToMany(Post::class, 'category_post', 'category_id', 'post_id');
    }
}
