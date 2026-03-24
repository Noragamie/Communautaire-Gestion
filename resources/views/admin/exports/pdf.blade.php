<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Export Profils</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4F46E5; color: white; }
    </style>
</head>
<body>
    <h1>Liste des profils approuvés</h1>
    <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Catégorie</th>
                <th>Localisation</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profiles as $profile)
                <tr>
                    <td>{{ $profile->user->name }}</td>
                    <td>{{ $profile->user->email }}</td>
                    <td>{{ $profile->category->name }}</td>
                    <td>{{ $profile->localisation }}</td>
                    <td>{{ $profile->telephone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
