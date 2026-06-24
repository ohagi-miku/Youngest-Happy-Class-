<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    #show/get specific user
    public function show($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.show')->with('user', $user);
    }

    #edit user
    public function edit()
    {
        $user = $this->user->findOrFail(Auth::user()->id);

        $close_friends = $user->closeFriends()->with('closeFriendUser')->get();

        $close_friend_ids = $close_friends->pluck('close_friend_id')->toArray();
        $followers = $user->followers()
            ->with('follower')
            ->get()
            ->filter(function ($follow) use ($close_friend_ids) {
                return !in_array($follow->follower_id, $close_friend_ids);
            });

        return view('users.profile.edit')
            ->with('user', $user)
            ->with('close_friends', $close_friends)
            ->with('followers', $followers);
    }

    #updata profile info
    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required|min:1|max:50',
            'email'         => 'required|email|max:50|unique:users,email,' . Auth::user()->id,
            // unique:table,column,pk value
            // The email must be unique in the users table, except for the logged in user's ID
            'avatar'        => 'mimes:jpg,jpeg,gif,png|max:1048',
            'introduction'  => 'max:100'
        ]);

        $user               = $this->user->findOrFail(Auth::user()->id);
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->introduction = $request->introduction;

        if ($request->avatar) {
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }
        $user->save();

        return redirect()->route('profile.show', Auth::user()->id);
    }

    public function followers($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.followers')->with('user', $user);
    }

    public function following($id)
    {
        $user = $this->user->findOrFail($id);
        return view('users.profile.following')->with('user', $user);
    }
}
