<?php

namespace App\Services;

use App\Mail\AdminNewProfileMail;
use App\Mail\ProfileApprovedMail;
use App\Mail\ProfileRejectedMail;
use App\Mail\ProfileSubmittedMail;
use App\Models\Document;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\NewProfileSubmitted;
use App\Notifications\ProfileApproved;
use App\Notifications\ProfileRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function submit(Request $request, User $user): Profile
    {
        return DB::transaction(function () use ($request, $user) {
            $profile = Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'category_id'     => $request->category_id,
                    'bio'             => $request->bio,
                    'competences'     => $request->competences,
                    'experience'      => $request->experience,
                    'localisation'    => $request->localisation,
                    'secteur_activite'=> $request->secteur_activite,
                    'telephone'       => $request->telephone,
                    'site_web'        => $request->site_web,
                    'niveau_etude'    => $request->niveau_etude,
                    'status'          => 'pending',
                ]
            );

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('photos', 'public');
                $profile->update(['photo' => $path]);
            }

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $type => $file) {
                    $path = $file->store('documents', 'public');
                    Document::create([
                        'profile_id'    => $profile->id,
                        'type'          => $type,
                        'path'          => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type'     => $file->getMimeType(),
                        'size'          => $file->getSize(),
                    ]);
                }
            }

            Mail::to($user->email)->queue(new ProfileSubmittedMail($profile));

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                if ($admin->notify_new_profile) {
                    Mail::to($admin->email)->queue(new AdminNewProfileMail($profile));
                }
                $admin->notify(new NewProfileSubmitted($profile));
            }

            return $profile;
        });
    }

    public function approve(Profile $profile): void
    {
        $profile->update(['status' => 'approved', 'motif_rejet' => null]);
        Mail::to($profile->user->email)->queue(new ProfileApprovedMail($profile));
        $profile->user->notify(new ProfileApproved($profile));
    }

    public function reject(Profile $profile, string $motif): void
    {
        $profile->update(['status' => 'rejected', 'motif_rejet' => $motif]);
        Mail::to($profile->user->email)->queue(new ProfileRejectedMail($profile, $motif));
        $profile->user->notify(new ProfileRejected($profile, $motif));
    }

    public function suspend(Profile $profile): void
    {
        $profile->update(['status' => 'suspended']);
    }

    public function deleteDocument(Document $document): void
    {
        Storage::disk('public')->delete($document->path);
        $document->delete();
    }
}
