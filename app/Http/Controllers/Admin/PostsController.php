<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post; 

class PostsController extends Controller
{
    public function index()
    {
        // get all posts
        $all_posts = Post::withTrashed()->latest()->paginate(5);

        return view('admin.posts.index', compact('all_posts'));
    }

    public function hide($id)
    {
        Post::destroy($id);

        return redirect()->back();
    }

    public function unhide($id)
    {
        // 削除された投稿も含めて探し、復元（restore）する
        Post::withTrashed()->findOrFail($id)->restore();

        return redirect()->back();
    }
}