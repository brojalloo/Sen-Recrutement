<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function uploadAvatar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'avatar' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        // `store()` renvoie false si l'écriture échoue (disque plein, droits).
        // Sans ce garde-fou, `url(false)` produisait l'URL du dossier de
        // stockage, enregistrée telle quelle : un avatar cassé, en silence.
        $path = $request->file('avatar')->store('avatars', 'public');

        if ($path === false) {
            return back()->with('error', "L'avatar n'a pas pu être enregistré. Réessayez.");
        }

        $user = Auth::user();
        $user->avatar = Storage::disk('public')->url($path);
        $user->save();

        return back()->with('status', 'Avatar mis à jour');
    }
}
