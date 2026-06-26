<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CloseFriend;
use App\Models\User;

class CloseFriendController extends Controller
{
    public function store(Request $request)
    {
        CloseFriend::create([
            'user_id' => Auth::user()->id,
            'close_friend_id' => $request->close_friend_id,
        ]);

        $newFriend = User::find($request->close_friend_id);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $newFriend->id,
                'name' => $newFriend->name,
                'avatar' => $newFriend->avatar,
            ]
        ]);
    }

    public function destroy(Request $request)
    {
        CloseFriend::where('user_id', Auth::user()->id)
            ->where('close_friend_id', $request->close_friend_id)
            ->delete();

        return response()->json(['success' => true]);
    }
}
