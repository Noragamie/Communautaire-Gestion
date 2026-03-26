<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AnnouncementMail;
use App\Services\ActivityLogger;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')->latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
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
            $data['user_id']      = Auth::id();
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? now() : null;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('announcements', 'public');
            }

            $announcement = Announcement::create($data);

            if ($publish) {
                $this->notifyValidatedUsers($announcement);
            }

            ActivityLogger::log($publish ? 'announcement_published' : 'announcement_created', 'Announcement', $announcement->id, $announcement->title);
            $message = $publish ? 'Annonce publiée et utilisateurs notifiés.' : 'Annonce enregistrée comme brouillon.';
            return redirect()->route('admin.announcements.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création de l\'annonce.'])->withInput();
        }
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
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
            $wasPublished = $announcement->is_published;
            $publish = $request->input('action') === 'publish';

            $data = $request->only('title', 'content');
            $data['is_published'] = $publish;
            $data['published_at'] = $publish ? ($announcement->published_at ?? now()) : null;

            if ($request->hasFile('image')) {
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                $data['image'] = $request->file('image')->store('announcements', 'public');
            }

            $announcement->update($data);

            if ($publish && !$wasPublished) {
                $this->notifyValidatedUsers($announcement);
            }

            ActivityLogger::log($publish ? 'announcement_published' : 'announcement_updated', 'Announcement', $announcement->id, $announcement->title);
            $message = $publish ? 'Annonce publiée.' : 'Brouillon enregistré.';
            return redirect()->route('admin.announcements.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.'])->withInput();
        }
    }

    public function destroy(Announcement $announcement)
    {
        try {
            $label = $announcement->title;
            $id    = $announcement->id;
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $announcement->delete();
            ActivityLogger::log('announcement_deleted', 'Announcement', $id, $label);
            return back()->with('success', 'Annonce supprimée.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }

    private function notifyValidatedUsers(Announcement $announcement): void
    {
        $users = User::where('is_active', true)->where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new AnnouncementMail($announcement));
        }

        Notification::send($users, new AnnouncementPublished($announcement));
    }
}
