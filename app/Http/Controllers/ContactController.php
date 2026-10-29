<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // La clé 'message' est réservée : Mail::send injecte l'objet Message
        // dans la vue et écraserait le texte du visiteur.
        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'body' => $data['message'],
        ];

        Mail::send('emails.contact', $payload, function ($m) use ($data) {
            $m->to(config('mail.from.address'))
                ->replyTo($data['email'], $data['name'])
                ->subject('[Contact] '.$data['subject']);
        });

        return back()->with('status', 'Message envoyé avec succès.');
    }
}
