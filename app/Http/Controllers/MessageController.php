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
       return view('users.messages.create');
    }


}
