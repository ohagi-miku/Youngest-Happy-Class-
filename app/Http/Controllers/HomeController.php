<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\CloseFriend;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $post;

    private $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $all_posts = $this->post->latest()->get(); //get all posts from recent posted
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users);
    }

    # Filter the homepage. Get the posts of the uses that the AUTH USER is following
    public function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = [];

        foreach ($all_posts as $post) {
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id) {

                // Check if the post is for close friends only
                if ($post->is_close_friend) {
                    // If it's the auth user's own post, show it
                    if ($post->user->id === Auth::user()->id) {
                        $home_posts[] = $post;
                    }
                    // If the auth user is in the poster's close friends list, show it
                    elseif (CloseFriend::where('user_id', $post->user->id)
                        ->where('close_friend_id', Auth::user()->id)
                        ->exists()
                    ) {
                        $home_posts[] = $post;
                    }
                } else {
                    // Normal post, show it
                    $home_posts[] = $post;
                }
            }
        }
        return $home_posts;
    }

    # Get the users the AUTH USER doesn't follow
    public function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id); //get all jusers except logged in users
        $suggested_users = []; //array for suggested users

        foreach ($all_users as $user) { //loop throught all users
            if (!$user->isFollowed()) {
                // get the NOT followed users
                $suggested_users[] = $user; // and put the data inside the array
            }
        }
        return $suggested_users;
    }

    public function search(Request $request)
    {
        $keyword = $request->search;

        $users = $this->user
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('introduction', 'like', '%' . $keyword . '%');
            })
            ->where('id', '!=', Auth::user()->id)
            ->get();

        $category = Category::where('name', $keyword)->first();
        $posts = $category ? $category->posts()->latest()->get() : collect();

        return view('users.search')
            ->with('search', $keyword)
            ->with('users', $users)
            ->with('posts', $posts);
    }
}
