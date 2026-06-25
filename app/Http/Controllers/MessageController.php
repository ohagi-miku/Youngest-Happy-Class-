<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;


class MessageController extends Controller
{
    private $message;

    private $user;

    public function __construct(Message $message, User $user){
        $this->message = $message;
        $this->user = $user;
    }

    public function index(){
        $all_messages = $this->message->latest()->get();
        return view('users.messages.index')->with('all_messages', $all_messages);
    }

    public function create() {
        $friend_users = $this->getFriendUsers();

        return view('users.messages.create')->with('friend_users', $friend_users);
    }

    public function getFriendUsers() {
       $all_users = $this->user->all()->except(Auth::user()->id);
       $friend_users = [];

       foreach($all_users as $user) { 
            if($user->isFollowed() && $user->followsMe()) {
                
                $friend_users[] = $user; 
            }
        }
        return $friend_users;
    }

    public function store(Request $request) {
       $request->validate([
           'message' => 'required',
           'receiver' => 'required'
       ]);

       $this->message->sender_id = Auth::user()->id;
       $this->message->message = $request->message;
       $this->message->receiver_id = $request->receiver;

       $this->message->save();

       return redirect()->route('message.index');
    }

    


}
