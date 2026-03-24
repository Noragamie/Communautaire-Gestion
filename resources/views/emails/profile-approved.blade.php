<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Profil approuvé !</h2>
    <p>Bonjour {{ $profile->user->name }},</p>
    <p>Félicitations ! Votre profil a été approuvé et est maintenant visible publiquement.</p>
    <p><a href="{{ route('profiles.show', $profile) }}" style="color: #4F46E5;">Voir mon profil public</a></p>
    <p>Cordialement,<br>L'équipe Commune</p>
</body>
</html>
