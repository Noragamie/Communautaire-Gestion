<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Modification approuvée !</h2>
    <p>Bonjour {{ $profile->user->name }},</p>
    <p>Votre demande de modification de profil a été <strong>approuvée</strong>. Votre profil mis à jour est maintenant visible publiquement.</p>
    <p><a href="{{ route('profiles.show', $profile) }}" style="color: #4F46E5;">Voir mon profil</a></p>
    <p>Cordialement,<br>L'équipe CommunePro</p>
</body>
</html>
