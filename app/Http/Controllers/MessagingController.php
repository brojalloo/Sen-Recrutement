<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        // Ne charger que les colonnes affichées : le reste du profil
        // (téléphone, adresse, bio) n'a rien à faire dans la vue.
        $users = User::contactableBy(Auth::user())
            ->orderBy('email')
            ->get(['id', 'name', 'first_name', 'last_name', 'email']);

        return view('messaging.compose', compact('users'));
    }

    public function send(Request $request)
    {
        // Même source de vérité que compose() : filtrer la liste sans valider
        // l'envoi laisserait passer n'importe quel recipient_id posté à la main.
        $allowed = User::contactableBy(Auth::user())->pluck('id')->all();

        $data = $request->validate([
            'recipient_id' => ['required', Rule::in($allowed)],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'type' => ['nullable', 'string'],
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $data['recipient_id'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'contact',
            'is_read' => false,
        ]);

        return redirect()->route('messages.outbox')->with('status', 'Message envoyé');
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

        return back()->with('status', 'Message marqué comme lu');
    }
}
