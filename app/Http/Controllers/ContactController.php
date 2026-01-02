<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Mail::send('emails.contact', $data, function ($m) use ($data) {
            $m->to(config('mail.from.address'))
              ->subject('[Contact] ' . $data['subject']);
        });

        return back()->with('status', 'Message envoyé avec succès.');
    }
}
