<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\Actuality;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ActualityController extends Controller
{
    public function index()
    {
        $actualities = Actuality::with('author')->latest()->paginate(15);
        return view('admin.actualities.index', compact('actualities'));
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
            $data = $request->only('title','content');
            $data['user_id'] = Auth::id();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('actualities','public');
            }

            Actuality::create($data);
            return redirect()->route('admin.actualities.index')->with('success', 'Actualité créée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'actualité.'])->withInput();
        }
    }

    public function publish(Actuality $actuality)
    {
        try {
            $actuality->update(['is_published' => true, 'published_at' => now()]);

            Newsletter::where('subscribed', true)->each(function ($subscriber) use ($actuality) {
                Mail::to($subscriber->email)->queue(new NewsletterMail($actuality, $subscriber->token));
            });

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
