<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Visitor\PublicProfileController;
use App\Http\Controllers\Operator\OperatorProfileController;
use App\Http\Controllers\Admin\{
    DashboardController, AdminProfileController, CategoryController,
    ActualityController, AnnouncementController, ExportController, UserController, SettingsController
};
use App\Http\Controllers\Operator\OperatorAnnouncementController;

// PUBLIC (visiteurs)
Route::get('/', [PublicProfileController::class, 'index'])->name('home');
Route::get('/profils', [PublicProfileController::class, 'index'])->name('profiles.index');
Route::get('/profils/{profile}', [PublicProfileController::class, 'show'])->name('profiles.show');
Route::get('/annuaire', [PublicProfileController::class, 'annuaire'])->name('annuaire');
Route::get('/annuaire/{category}', [PublicProfileController::class, 'byCategory'])->name('category.show');
Route::get('/actualites', [PublicProfileController::class, 'actualities'])->name('actualities');
Route::get('/discussions', function () { return view('visitor.discussions'); })->name('discussions');
Route::post('/newsletter/subscribe', [PublicProfileController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [PublicProfileController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');

// AUTH
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);
    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);
});
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// NOTIFICATIONS (tous les utilisateurs connectés)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/{id}/lire', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/tout-lire', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

// OPÉRATEUR
Route::prefix('mon-espace')->name('operator.')->middleware(['auth', 'active', 'role:operateur'])->group(function () {
    Route::get('/profil', [OperatorProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/creer', [OperatorProfileController::class, 'create'])->name('profile.create');
    Route::post('/profil', [OperatorProfileController::class, 'store'])->name('profile.store');
    Route::get('/profil/modifier', [OperatorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [OperatorProfileController::class, 'update'])->name('profile.update');
    Route::delete('/documents/{document}', [OperatorProfileController::class, 'destroyDocument'])->name('document.destroy');
    Route::get('/annonces', [OperatorAnnouncementController::class, 'index'])->name('announcements.index');
});

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profils
    Route::get('/profils', [AdminProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profils/{profile}', [AdminProfileController::class, 'show'])->name('profiles.show');
    Route::post('/profils/{profile}/approuver', [AdminProfileController::class, 'approve'])->name('profiles.approve');
    Route::post('/profils/{profile}/rejeter', [AdminProfileController::class, 'reject'])->name('profiles.reject');
    Route::post('/profils/{profile}/suspendre', [AdminProfileController::class, 'suspend'])->name('profiles.suspend');
    Route::delete('/profils/{profile}', [AdminProfileController::class, 'destroy'])->name('profiles.destroy');

    // Catégories
    Route::resource('categories', CategoryController::class)->except(['show','create','edit']);

    // Actualités
    Route::get('/actualites', [ActualityController::class, 'index'])->name('actualities.index');
    Route::get('/actualites/creer', [ActualityController::class, 'create'])->name('actualities.create');
    Route::post('/actualites', [ActualityController::class, 'store'])->name('actualities.store');
    Route::get('/actualites/{actuality}/modifier', [ActualityController::class, 'edit'])->name('actualities.edit');
    Route::put('/actualites/{actuality}', [ActualityController::class, 'update'])->name('actualities.update');
    Route::post('/actualites/{actuality}/publier', [ActualityController::class, 'publish'])->name('actualities.publish');
    Route::delete('/actualites/{actuality}', [ActualityController::class, 'destroy'])->name('actualities.destroy');

    // Exports
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');

    // Utilisateurs
    Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
    Route::post('/utilisateurs/{user}/toggle-actif', [UserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/utilisateurs/{user}/logs', [UserController::class, 'authLogs'])->name('users.logs');

    // Annonces
    Route::get('/annonces', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/annonces/creer', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/annonces', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/annonces/{announcement}/modifier', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/annonces/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/annonces/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Paramètres
    Route::get('/parametres', [SettingsController::class, 'index'])->name('settings');
    Route::put('/parametres/compte', [SettingsController::class, 'updateAccount'])->name('settings.account');
    Route::put('/parametres/mot-de-passe', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/parametres/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
});
