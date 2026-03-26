<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Modification refusée</h2>
    <p>Bonjour {{ $profile->user->name }},</p>
    <p>Votre demande de modification de profil a été <strong>refusée</strong>.</p>
    <p><strong>Motif :</strong> {{ $motif }}</p>
    <p>Votre profil actuel reste visible tel quel. Vous pouvez soumettre une nouvelle modification en tenant compte de ce retour.</p>
    <p><a href="{{ route('operator.profile.edit') }}" style="color: #4F46E5;">Modifier mon profil</a></p>
    <p>Cordialement,<br>L'équipe CommunePro</p>
</body>
</html>
