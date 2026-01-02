<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class MessagingController extends Controller
{
    public function inbox()
    {
        $messages = Message::query()->where('recipient_id', Auth::id())->orderByDesc('created_at')->paginate(20);
        return view('messaging.inbox', compact('messages'));
    }

    public function outbox()
    {
        $messages = Message::query()->where('sender_id', Auth::id())->orderByDesc('created_at')->paginate(20);
        return view('messaging.outbox', compact('messages'));
    }

    public function compose()
    {
        $users = User::query()->orderBy('email')->limit(100)->get();
        return view('messaging.compose', compact('users'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'recipient_id' => ['required','exists:users,id'],
            'subject' => ['required','string','max:255'],
            'message' => ['required','string','max:5000'],
            'type' => ['nullable','string']
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $data['recipient_id'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'contact',
            'is_read' => false,
        ]);

        return redirect()->route('messages.outbox')->with('status','Message envoyé');
    }

    public function markRead($id)
    {
        $msg = Message::findOrFail($id);
        if ($msg->recipient_id !== Auth::id()) {
            abort(403);
        }
        $msg->is_read = true;
        $msg->read_at = now();
        $msg->save();
        return back()->with('status','Message marqué comme lu');
    }
}
