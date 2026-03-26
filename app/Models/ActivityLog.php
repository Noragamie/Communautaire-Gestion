<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'role', 'action', 'target_type', 'target_id', 'target_label', 'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function actionLabel(string $action): string
    {
        return match($action) {
            'profile_approved'       => 'Profil approuvé',
            'profile_rejected'       => 'Profil rejeté',
            'profile_suspended'      => 'Profil suspendu',
            'profile_deleted'        => 'Profil supprimé',
            'profile_created'        => 'Profil créé',
            'profile_updated'        => 'Profil modifié',
            'user_activated'         => 'Compte activé',
            'user_deactivated'       => 'Compte désactivé',
            'user_suspended'         => 'Compte suspendu',
            'user_unsuspended'       => 'Suspension levée',
            'user_deleted'           => 'Utilisateur supprimé',
            'category_created'       => 'Catégorie créée',
            'category_updated'       => 'Catégorie modifiée',
            'category_deleted'       => 'Catégorie supprimée',
            'actuality_created'      => 'Actualité créée',
            'actuality_published'    => 'Actualité publiée',
            'actuality_updated'      => 'Actualité modifiée',
            'actuality_deleted'      => 'Actualité supprimée',
            'announcement_created'   => 'Annonce créée',
            'announcement_published' => 'Annonce publiée',
            'announcement_updated'   => 'Annonce modifiée',
            'announcement_deleted'   => 'Annonce supprimée',
            'document_deleted'       => 'Document supprimé',
            default                  => $action,
        };
    }

    public static function actionColor(string $action): string
    {
        return match(true) {
            str_ends_with($action, '_deleted') || str_ends_with($action, '_rejected') => 'red',
            str_ends_with($action, '_suspended') || str_ends_with($action, '_deactivated') || $action === 'user_suspended' => 'orange',
            str_ends_with($action, '_approved') || str_ends_with($action, '_activated') || str_ends_with($action, '_published') => 'green',
            str_ends_with($action, '_created') => 'blue',
            default => 'gray',
        };
    }
}
