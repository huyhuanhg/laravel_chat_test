<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $message = $request->user()->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($request->user(), $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}

