<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Mise à jour de votre profil</h2>
    <p>Bonjour {{ $profile->user->name }},</p>
    <p>Votre profil nécessite des modifications avant d'être approuvé.</p>
    <p><strong>Motif :</strong> {{ $motif }}</p>
    <p><a href="{{ route('operator.profile.edit') }}" style="color: #4F46E5;">Modifier mon profil</a></p>
    <p>Cordialement,<br>L'équipe Commune</p>
</body>
</html>
