<?php

/**
 * Script pour migrer les images existantes vers base64
 * 
 * Usage: php migrate_existing_images.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Announcement;
use App\Models\Actuality;
use App\Models\Profile;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

echo "🔄 Migration des images existantes vers base64...\n\n";

// Migrer les annonces
echo "📢 Annonces:\n";
$announcements = Announcement::whereNotNull('image')->whereNull('image_data')->get();
foreach ($announcements as $announcement) {
    $path = storage_path('app/public/' . $announcement->image);
    if (file_exists($path)) {
        $imageData = base64_encode(file_get_contents($path));
        $mimeType = mime_content_type($path);
        $announcement->update([
            'image_data' => "data:{$mimeType};base64,{$imageData}"
        ]);
        echo "  ✅ {$announcement->title}\n";
    } else {
        echo "  ⚠️  {$announcement->title} - Fichier introuvable\n";
    }
}
echo "  Total: " . $announcements->count() . " annonces\n\n";

// Migrer les actualités
echo "📰 Actualités:\n";
$actualities = Actuality::whereNotNull('image')->whereNull('image_data')->get();
foreach ($actualities as $actuality) {
    $path = storage_path('app/public/' . $actuality->image);
    if (file_exists($path)) {
        $imageData = base64_encode(file_get_contents($path));
        $mimeType = mime_content_type($path);
        $actuality->update([
            'image_data' => "data:{$mimeType};base64,{$imageData}"
        ]);
        echo "  ✅ {$actuality->title}\n";
    } else {
        echo "  ⚠️  {$actuality->title} - Fichier introuvable\n";
    }
}
echo "  Total: " . $actualities->count() . " actualités\n\n";

// Migrer les photos de profil
echo "👤 Profils:\n";
$profiles = Profile::whereNotNull('photo')->whereNull('photo_data')->get();
foreach ($profiles as $profile) {
    $path = storage_path('app/public/' . $profile->photo);
    if (file_exists($path)) {
        $imageData = base64_encode(file_get_contents($path));
        $mimeType = mime_content_type($path);
        $profile->update([
            'photo_data' => "data:{$mimeType};base64,{$imageData}"
        ]);
        echo "  ✅ {$profile->user->name}\n";
    } else {
        echo "  ⚠️  {$profile->user->name} - Fichier introuvable\n";
    }
}
echo "  Total: " . $profiles->count() . " profils\n\n";

// Migrer les documents
echo "📄 Documents:\n";
$documents = Document::whereNotNull('path')->whereNull('file_data')->get();
foreach ($documents as $document) {
    $path = storage_path('app/public/' . $document->path);
    if (file_exists($path)) {
        $fileData = base64_encode(file_get_contents($path));
        $mimeType = mime_content_type($path);
        $document->update([
            'file_data' => "data:{$mimeType};base64,{$fileData}"
        ]);
        echo "  ✅ {$document->original_name}\n";
    } else {
        echo "  ⚠️  {$document->original_name} - Fichier introuvable\n";
    }
}
echo "  Total: " . $documents->count() . " documents\n\n";

echo "✅ Migration terminée!\n";
