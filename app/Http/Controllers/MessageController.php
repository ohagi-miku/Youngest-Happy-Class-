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
        $authId = Auth::id();

        $messages = $this->message
            ->with('sender', 'receiver')
            ->where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->latest()
            ->get();
        
        $all_messages = $messages->groupBy(function ($message) use ($authId) {
            return $message->sender_id === $authId ? $message->receiver_id : $message->sender_id;
        })->map(function ($group) {
            return $group->first();
        });
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

    // public function store(Request $request) {
    //    $request->validate([
    //        'message' => 'required',
    //        'receiver' => 'required'
    //    ]);

    //    $this->message->sender_id = Auth::user()->id;
    //    $this->message->message = $request->message;
    //    $this->message->receiver_id = $request->receiver;

    //    $this->message->save();

    //    return redirect()->route('message.index');
    // }

    public function store(Request $request, User $user) {
        $request->validate([
            'message' => 'nullable|string|max:1000|required_without:image',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120|required_without:message',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('messages', 'public');
        }

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'message'     => $request->message,
            'image'       => $imagePath,
        ]);

        return redirect()->route('message.show', $user->id);
    }


    public function show(User $user) {
    $authId = Auth::id();

    $messages = $this->message
        ->where(function ($query) use ($authId, $user) {
            $query->where('sender_id', $authId)
                  ->where('receiver_id', $user->id);
        })
        ->orWhere(function ($query) use ($authId, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $authId);
        })
        ->oldest() 
        ->get();

    return view('users.messages.show')
        ->with('messages', $messages)
        ->with('partner', $user);
}

    


}
