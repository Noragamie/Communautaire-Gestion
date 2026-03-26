<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeNewsletterMail;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class OperatorSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $newsletter = $user->newsletter;

        return view('operator.settings', compact('user', 'newsletter'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required'  => 'Le nom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email'    => 'L\'email n\'est pas valide.',
            'email.unique'   => 'Cet email est déjà utilisé.',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Informations mises à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis.',
            'password.required'         => 'Le nouveau mot de passe est requis.',
            'password.confirmed'        => 'La confirmation ne correspond pas.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        Auth::user()->update(['password' => $request->password]);

        return back()->with('success', 'Mot de passe modifié.');
    }

    public function updateNewsletter(Request $request)
    {
        $user = Auth::user();
        $subscribe = $request->boolean('newsletter_subscribed');

        $newsletter = Newsletter::firstOrNew(['email' => $user->email]);

        if ($subscribe) {
            $isNew = !$newsletter->exists;
            $newsletter->subscribed = true;
            $newsletter->user_id    = $user->id;
            $newsletter->save();

            if ($isNew) {
                Mail::to($user->email)->send(new WelcomeNewsletterMail($newsletter->token));
            }
        } else {
            if ($newsletter->exists) {
                $newsletter->update(['subscribed' => false]);
            }
        }

        return back()->with('success', 'Préférence newsletter mise à jour.');
    }
}
