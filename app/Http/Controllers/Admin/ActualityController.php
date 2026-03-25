<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\Actuality;
use App\Models\Newsletter;
use App\Models\User;
use App\Notifications\ActualityPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ActualityController extends Controller
{
    public function index()
    {
        $actualities = Actuality::with('author')->latest()->paginate(15);
        return view('admin.actualities.index', compact('actualities'));
    }

    public function create()
    {
        return view('admin.actualities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|max:2048',
        ], [
            'title.required' => 'Le titre est requis.',
            'content.required' => 'Le contenu est requis.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        try {
            $publish = $request->input('action') === 'publish';

            $data = $request->only('title', 'content');
            $data['user_id']      = Auth::id();
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? now() : null;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('actualities', 'public');
            }

            $actuality = Actuality::create($data);

            if ($publish) {
                Newsletter::where('subscribed', true)->each(function ($subscriber) use ($actuality) {
                    Mail::to($subscriber->email)->send(new NewsletterMail($actuality, $subscriber->token));
                });
                $users = User::where('is_active', true)->get();
                Notification::send($users, new ActualityPublished($actuality));
            }

            $message = $publish ? 'Actualité publiée et newsletter envoyée.' : 'Actualité enregistrée comme brouillon.';
            return redirect()->route('admin.actualities.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'actualité.'])->withInput();
        }
    }

    public function edit(Actuality $actuality)
    {
        return view('admin.actualities.edit', compact('actuality'));
    }

    public function update(Request $request, Actuality $actuality)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|max:2048',
        ], [
            'title.required'   => 'Le titre est requis.',
            'content.required' => 'Le contenu est requis.',
            'image.image'      => 'Le fichier doit être une image.',
            'image.max'        => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        try {
            $publish = $request->input('action') === 'publish';

            $data = $request->only('title', 'content');
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? ($actuality->published_at ?? now()) : null;

            if ($request->hasFile('image')) {
                if ($actuality->image) {
                    \Storage::disk('public')->delete($actuality->image);
                }
                $data['image'] = $request->file('image')->store('actualities', 'public');
            }

            $actuality->update($data);

            if ($publish && !$actuality->wasRecentlyCreated) {
                Newsletter::where('subscribed', true)->each(function ($subscriber) use ($actuality) {
                    Mail::to($subscriber->email)->send(new NewsletterMail($actuality, $subscriber->token));
                });
                $users = User::where('is_active', true)->get();
                Notification::send($users, new ActualityPublished($actuality));
            }

            $message = $publish ? 'Actualité publiée.' : 'Brouillon enregistré.';
            return redirect()->route('admin.actualities.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.'])->withInput();
        }
    }

    public function publish(Actuality $actuality)
    {
        try {
            $actuality->update(['is_published' => true, 'published_at' => now()]);

            Newsletter::where('subscribed', true)->each(function ($subscriber) use ($actuality) {
                Mail::to($subscriber->email)->send(new NewsletterMail($actuality, $subscriber->token));
            });
            $users = User::where('is_active', true)->get();
            Notification::send($users, new ActualityPublished($actuality));

            return back()->with('success', 'Actualité publiée et newsletter envoyée aux abonnés.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la publication.']);
        }
    }

    public function destroy(Actuality $actuality)
    {
        try {
            $actuality->delete();
            return back()->with('success', 'Actualité supprimée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
