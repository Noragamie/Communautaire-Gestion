<?php

namespace App\Services;

use App\Mail\AdminNewProfileMail;
use App\Mail\ModificationApprovedMail;
use App\Mail\ModificationRejectedMail;
use App\Mail\ProfileApprovedMail;
use App\Mail\ProfileRejectedMail;
use App\Mail\ProfileSubmittedMail;
use App\Models\Document;
use App\Models\ModificationRequest;
use App\Models\ModificationRequestDocument;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\ModificationApproved;
use App\Notifications\ModificationRejected;
use App\Notifications\NewModificationRequested;
use App\Notifications\NewProfileSubmitted;
use App\Notifications\NewProfileSubmittedForAdmin;
use App\Notifications\ProfileApproved;
use App\Notifications\ProfileRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * @return Collection<int, User>
     */
    private function backofficeRecipientsForCommune(?int $communeId)
    {
        return User::query()
            ->where(function ($q) use ($communeId) {
                $q->where('role', 'admin')
                    ->when($communeId, fn ($q2) => $q2->whereHas('managedCommunes', fn ($m) => $m->where('communes.id', $communeId)));
            })
            ->when($communeId, fn ($q) => $q->orWhere(fn ($q2) => $q2->where('role', 'agent_municipal')->where('commune_id', $communeId)))
            ->get()
            ->unique('id');
    }

    public function submit(Request $request, User $user): Profile
    {
        return DB::transaction(function () use ($request, $user) {
            $user->loadMissing('commune');
            $localisation = $request->localisation ?: ($user->commune?->name);

            $profile = Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'category_id' => $request->category_id,
                    'bio' => $request->bio,
                    'competences' => $request->competences,
                    'experience' => $request->experience,
                    'localisation' => $localisation,
                    'secteur_activite' => $request->secteur_activite,
                    'telephone' => $request->telephone,
                    'site_web' => $request->site_web,
                    'niveau_etude' => $request->niveau_etude,
                    'status' => 'pending',
                    'contact_visible' => $request->boolean('contact_visible', true),
                ]
            );

            if ($request->hasFile('photo')) {
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $path = $request->file('photo')->store('photos', 'public');
                $profile->update(['photo' => $path]);
            }

            if ($request->hasFile('documents.cv')) {
                $profile->documents()->where('type', 'cv')->get()->each(function (Document $doc) {
                    Storage::disk('public')->delete($doc->path);
                    $doc->delete();
                });
                $file = $request->file('documents.cv');
                $path = $file->store('documents/cv', 'public');
                Document::create([
                    'profile_id' => $profile->id,
                    'type' => 'cv',
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            if ($request->hasFile('documents.other')) {
                foreach ($request->file('documents.other') as $file) {
                    if (! $file->isValid()) {
                        continue;
                    }
                    $path = $file->store('documents/other', 'public');
                    Document::create([
                        'profile_id' => $profile->id,
                        'type' => 'autre',
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            Mail::to($user->email)->queue(new ProfileSubmittedMail($profile));

            foreach ($this->backofficeRecipientsForCommune($user->commune_id) as $recipient) {
                if ($recipient->isAdmin() && $recipient->notify_new_profile) {
                    Mail::to($recipient->email)->queue(new AdminNewProfileMail($profile));
                }
                if ($recipient->isAdmin()) {
                    $recipient->notify(new NewProfileSubmittedForAdmin($profile));
                } else {
                    $recipient->notify(new NewProfileSubmitted($profile));
                }
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

    public function submitModificationRequest(Request $request, Profile $profile): ModificationRequest
    {
        return DB::transaction(function () use ($request, $profile) {
            $modRequest = ModificationRequest::create([
                'profile_id' => $profile->id,
                'status' => 'pending',
                'data' => [
                    'category_id' => $request->category_id,
                    'bio' => $request->bio,
                    'competences' => $request->competences,
                    'experience' => $request->experience,
                    'localisation' => $request->localisation,
                    'secteur_activite' => $request->secteur_activite,
                    'telephone' => $request->telephone,
                    'site_web' => $request->site_web,
                    'niveau_etude' => $request->niveau_etude,
                    'contact_visible' => $request->boolean('contact_visible', true),
                ],
            ]);

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('modifications/photos', 'public');
                $modRequest->update(['new_photo' => $path]);
            }

            if ($request->hasFile('documents.cv')) {
                $file = $request->file('documents.cv');
                ModificationRequestDocument::create([
                    'modification_request_id' => $modRequest->id,
                    'type' => 'cv',
                    'path' => $file->store('modifications/cv', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            if ($request->hasFile('documents.other')) {
                foreach ($request->file('documents.other') as $file) {
                    ModificationRequestDocument::create([
                        'modification_request_id' => $modRequest->id,
                        'type' => 'autre',
                        'path' => $file->store('modifications/other', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            foreach ($this->backofficeRecipientsForCommune($profile->user->commune_id) as $recipient) {
                $recipient->notify(new NewModificationRequested($modRequest));
            }

            return $modRequest;
        });
    }

    public function approveModification(ModificationRequest $modRequest): void
    {
        DB::transaction(function () use ($modRequest) {
            $profile = $modRequest->profile;
            $data = $modRequest->data;

            // Appliquer les champs texte
            $profile->update($data);

            // Appliquer la nouvelle photo
            if ($modRequest->new_photo) {
                if ($profile->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $profile->update(['photo' => $modRequest->new_photo]);
            }

            // Appliquer les nouveaux documents
            if ($modRequest->documents->isNotEmpty()) {
                // Supprimer les anciens documents des types remplacés
                $newTypes = $modRequest->documents->pluck('type')->unique();
                $profile->documents()->whereIn('type', $newTypes)->each(function ($doc) {
                    Storage::disk('public')->delete($doc->path);
                    $doc->delete();
                });

                // Déplacer les documents de la demande vers le profil
                foreach ($modRequest->documents as $doc) {
                    Document::create([
                        'profile_id' => $profile->id,
                        'type' => $doc->type,
                        'path' => $doc->path,
                        'original_name' => $doc->original_name,
                        'mime_type' => $doc->mime_type,
                        'size' => $doc->size,
                    ]);
                }
                // Supprimer les entrées de la demande (fichiers déjà réutilisés)
                $modRequest->documents()->delete();
            }

            $modRequest->update(['status' => 'approved']);
            Mail::to($profile->user->email)->queue(new ModificationApprovedMail($profile));
            $profile->user->notify(new ModificationApproved($profile));
        });
    }

    public function rejectModification(ModificationRequest $modRequest, string $motif): void
    {
        DB::transaction(function () use ($modRequest, $motif) {
            // Supprimer les fichiers temporaires
            if ($modRequest->new_photo) {
                Storage::disk('public')->delete($modRequest->new_photo);
            }
            foreach ($modRequest->documents as $doc) {
                Storage::disk('public')->delete($doc->path);
            }

            $modRequest->update(['status' => 'rejected', 'motif_rejet' => $motif]);
            Mail::to($modRequest->profile->user->email)->queue(new ModificationRejectedMail($modRequest->profile, $motif));
            $modRequest->profile->user->notify(new ModificationRejected($modRequest->profile, $motif));
        });
    }
}
