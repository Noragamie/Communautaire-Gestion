<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Profil soumis avec succès</h2>
    <p>Bonjour {{ $profile->user->name }},</p>
    <p>Votre profil a bien été soumis et est en attente de validation par notre équipe.</p>
    <p>Vous recevrez un email dès que votre profil sera examiné.</p>
    <p>Cordialement,<br>L'équipe Commune</p>
</body>
</html>
