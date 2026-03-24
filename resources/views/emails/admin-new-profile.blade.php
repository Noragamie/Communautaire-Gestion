<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Nouveau profil en attente</h2>
    <p>Un nouveau profil a été soumis et attend votre validation.</p>
    <p><strong>Nom :</strong> {{ $profile->user->name }}</p>
    <p><strong>Catégorie :</strong> {{ $profile->category->name }}</p>
    <p><a href="{{ route('admin.profiles.show', $profile) }}" style="color: #4F46E5;">Examiner le profil</a></p>
</body>
</html>
