<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

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
    public function getHomePosts() {
        $all_posts = $this->post->latest()->get(); // get all posts
        $home_posts = []; //create array for AUTH USER posts and followed user posts

        foreach($all_posts as $post) { //loop throught all posts
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id) {
                // if the posts are from the followed users QR the LOGGED IN user's posts
                $home_posts[] = $post; //put data inside array home_posts
            }
        }
        return $home_posts; //return array
    }

    # Get the users the AUTH USER doesn't follow
    public function getSuggestedUsers() {
        $all_users = $this->user->all()->except(Auth::user()->id); //get all jusers except logged in users
        $suggested_users = []; //array for suggested users

        foreach($all_users as $user) { //loop throught all users
            if(!$user->isFollowed()) {
                // get the NOT followed users
                $suggested_users[] = $user; // and put the data inside the array
            }
        }
        return $suggested_users;
    }

    public function search(Request $request) {
        $users = $this->user->where('name', 'like', '%' .$request->search.'%')->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }
}
