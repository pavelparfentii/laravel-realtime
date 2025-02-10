<?php

namespace App\Http\Controllers;

use App\Events\GreetingEvent;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChat()
    {
        return view('chat.show');
    }

    public function messageReceived(Request $request)
    {
        $rules = [
            'message' => 'required',
        ];
        $request->validate($rules);

        broadcast( new MessageSent($request->user(), $request->message));

        return response()->json('Message broadcasted');
    }

    public function greetReceived(Request $request, User $user)
    {
        broadcast( new GreetingEvent($user, "{$request->user()->name} greeted you") );
        broadcast(new GreetingEvent($request->user(), "You greeted {$user->name}"));

        return "Greeting {$user->name} from {$request->user()->name}";
    }
}
