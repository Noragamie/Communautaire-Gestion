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
                $file = $request->file('photo');
                // Stocker en base64
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $profile->update([
                    'photo_data' => "data:{$mimeType};base64,{$imageData}",
                    'photo' => $file->getClientOriginalName()
                ]);
            }

            if ($request->hasFile('documents.cv')) {
                $profile->documents()->where('type', 'cv')->delete();
                $file = $request->file('documents.cv');
                // Stocker en base64
                $fileData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                Document::create([
                    'profile_id' => $profile->id,
                    'type' => 'cv',
                    'path' => $file->getClientOriginalName(),
                    'file_data' => "data:{$mimeType};base64,{$fileData}",
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
                    // Stocker en base64
                    $fileData = base64_encode(file_get_contents($file->getRealPath()));
                    $mimeType = $file->getMimeType();
                    Document::create([
                        'profile_id' => $profile->id,
                        'type' => 'autre',
                        'path' => $file->getClientOriginalName(),
                        'file_data' => "data:{$mimeType};base64,{$fileData}",
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
        $document->delete();
    }

    public function submitModificationRequest(Request $request, Profile $profile): ModificationRequest
    {
        return DB::transaction(function () use ($request, $profile) {
            \Log::info('Début submitModificationRequest', [
                'profile_id' => $profile->id,
                'user_id' => $profile->user_id,
                'commune_id' => $profile->user->commune_id
            ]);

            $data = [
                'category_id' => $request->category_id,
                'bio' => $request->bio,
                'competences' => $request->competences,
                'experience' => $request->experience,
                'localisation' => $request->filled('localisation')
                    ? $request->localisation
                    : ($profile->user->commune?->name ?? $profile->localisation),
                'secteur_activite' => $request->secteur_activite,
                'telephone' => $request->telephone,
                'site_web' => $request->site_web,
                'niveau_etude' => $request->niveau_etude,
                'contact_visible' => $request->boolean('contact_visible', true),
            ];

            \Log::info('Données à enregistrer', ['data' => $data]);

            $modRequest = ModificationRequest::create([
                'profile_id' => $profile->id,
                'status' => 'pending',
                'data' => $data,
            ]);

            \Log::info('ModificationRequest créée', ['id' => $modRequest->id]);

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $imageData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                $modRequest->update([
                    'new_photo' => $file->getClientOriginalName(),
                    'new_photo_data' => "data:{$mimeType};base64,{$imageData}"
                ]);
                \Log::info('Photo ajoutée', ['name' => $file->getClientOriginalName()]);
            }

            if ($request->hasFile('documents.cv')) {
                $file = $request->file('documents.cv');
                $fileData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();
                ModificationRequestDocument::create([
                    'modification_request_id' => $modRequest->id,
                    'type' => 'cv',
                    'path' => $file->getClientOriginalName(),
                    'file_data' => "data:{$mimeType};base64,{$fileData}",
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
                \Log::info('CV ajouté');
            }

            if ($request->hasFile('documents.other')) {
                foreach ($request->file('documents.other') as $file) {
                    $fileData = base64_encode(file_get_contents($file->getRealPath()));
                    $mimeType = $file->getMimeType();
                    ModificationRequestDocument::create([
                        'modification_request_id' => $modRequest->id,
                        'type' => 'autre',
                        'path' => $file->getClientOriginalName(),
                        'file_data' => "data:{$mimeType};base64,{$fileData}",
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
                \Log::info('Autres documents ajoutés');
            }

            $recipients = $this->backofficeRecipientsForCommune($profile->user->commune_id);
            \Log::info('Destinataires trouvés', ['count' => $recipients->count()]);

            foreach ($recipients as $recipient) {
                $recipient->notify(new NewModificationRequested($modRequest));
            }

            \Log::info('Notifications envoyées');

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
            if ($modRequest->new_photo_data) {
                $profile->update([
                    'photo_data' => $modRequest->new_photo_data,
                    'photo' => $modRequest->new_photo
                ]);
            }

            // Appliquer les nouveaux documents
            if ($modRequest->documents->isNotEmpty()) {
                // Supprimer les anciens documents des types remplacés
                $newTypes = $modRequest->documents->pluck('type')->unique();
                $profile->documents()->whereIn('type', $newTypes)->delete();

                // Déplacer les documents de la demande vers le profil
                foreach ($modRequest->documents as $doc) {
                    Document::create([
                        'profile_id' => $profile->id,
                        'type' => $doc->type,
                        'path' => $doc->path,
                        'file_data' => $doc->file_data,
                        'original_name' => $doc->original_name,
                        'mime_type' => $doc->mime_type,
                        'size' => $doc->size,
                    ]);
                }
                // Supprimer les entrées de la demande
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
            // Pas besoin de supprimer les fichiers (stockés en base64)
            $modRequest->update(['status' => 'rejected', 'motif_rejet' => $motif]);
            Mail::to($modRequest->profile->user->email)->queue(new ModificationRejectedMail($modRequest->profile, $motif));
            $modRequest->profile->user->notify(new ModificationRejected($modRequest->profile, $motif));
        });
    }
}
