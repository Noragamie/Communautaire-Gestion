<?php

use App\Http\Controllers\Admin\ActualityController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommuneSwitchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ModificationRequestController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Operator\OperatorAnnouncementController;
use App\Http\Controllers\Operator\OperatorProfileController;
use App\Http\Controllers\Operator\OperatorSettingsController;
use App\Http\Controllers\Visitor\PublicProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// PUBLIC (visiteurs)
Route::get('/', [PublicProfileController::class, 'index'])->name('home');
Route::get('/profils', [PublicProfileController::class, 'index'])->name('profiles.index');
Route::get('/profils/{profile}', [PublicProfileController::class, 'show'])->name('profiles.show');
Route::get('/annuaire', [PublicProfileController::class, 'annuaire'])->name('annuaire');
Route::get('/annuaire/{category}', [PublicProfileController::class, 'byCategory'])->name('category.show');
Route::get('/actualites', [PublicProfileController::class, 'actualities'])->name('actualities');
Route::post('/newsletter/subscribe', [PublicProfileController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [PublicProfileController::class, 'unsubscribeNewsletter'])->name('newsletter.unsubscribe');

// AUTH
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);
    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);
    Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [ResetPasswordController::class, 'reset'])->name('password.update');
});
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// VÉRIFICATION EMAIL
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        $user = $request->user();
        if ($user->isBackoffice()) {
            return redirect()->route('admin.dashboard')->with('success', 'Email confirmé ! Bienvenue.');
        }

        return redirect()->route('operator.profile.show')->with('success', 'Email confirmé ! Bienvenue.');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email de vérification renvoyé.');
    })->middleware('throttle:6,1')->name('verification.send');
});

// NOTIFICATIONS (tous les utilisateurs connectés)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/data', [NotificationController::class, 'data'])->name('notifications.data');
    Route::get('/notifications/{id}/lire', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/tout-lire', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

// OPÉRATEUR
Route::prefix('mon-espace')->name('operator.')->middleware(['auth', 'verified', 'active', 'role:operateur'])->group(function () {
    Route::get('/profil', [OperatorProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/creer', [OperatorProfileController::class, 'create'])->name('profile.create');
    Route::post('/profil', [OperatorProfileController::class, 'store'])->name('profile.store');
    Route::get('/profil/modifier', [OperatorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [OperatorProfileController::class, 'update'])->name('profile.update');
    Route::delete('/documents/{document}', [OperatorProfileController::class, 'destroyDocument'])->name('document.destroy');
    Route::patch('/profil/contact-visible', [OperatorProfileController::class, 'toggleContactVisible'])->name('profile.contact-visible');
    Route::get('/annonces', [OperatorAnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/parametres', [OperatorSettingsController::class, 'index'])->name('settings');
    Route::put('/parametres/compte', [OperatorSettingsController::class, 'updateAccount'])->name('settings.account');
    Route::put('/parametres/mot-de-passe', [OperatorSettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/parametres/newsletter', [OperatorSettingsController::class, 'updateNewsletter'])->name('settings.newsletter');
});

// ADMINISTRATION (administrateur + agent municipal)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'active', 'backoffice'])->group(function () {
    Route::post('/commune/active', [CommuneSwitchController::class, 'update'])->middleware('admin.only')->name('commune.active');

    Route::middleware('admin.only')->group(function () {
        Route::get('/utilisateurs/agents/creer', [UserController::class, 'createAgent'])->name('users.agents.create');
        Route::post('/utilisateurs/agents', [UserController::class, 'storeAgent'])->name('users.agents.store');

        Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);
        Route::post('/categories/{category}/reclassifier', [CategoryController::class, 'destroyWithReclassify'])->name('categories.reclassify');

        Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');

        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter');

        Route::get('/logs', [LogController::class, 'index'])->name('logs');
    });

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profils', [AdminProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profils/{profile}', [AdminProfileController::class, 'show'])->name('profiles.show');
    Route::post('/profils/{profile}/approuver', [AdminProfileController::class, 'approve'])->name('profiles.approve');
    Route::post('/profils/{profile}/rejeter', [AdminProfileController::class, 'reject'])->name('profiles.reject');
    Route::post('/profils/{profile}/suspendre', [AdminProfileController::class, 'suspend'])->name('profiles.suspend');
    Route::post('/profils/{profile}/exporter-documents', [AdminProfileController::class, 'exportDocuments'])->name('profiles.export-documents');
    Route::delete('/profils/{profile}', [AdminProfileController::class, 'destroy'])->name('profiles.destroy');

    Route::get('/actualites', [ActualityController::class, 'index'])->name('actualities.index');
    Route::get('/actualites/creer', [ActualityController::class, 'create'])->name('actualities.create');
    Route::post('/actualites', [ActualityController::class, 'store'])->name('actualities.store');
    Route::get('/actualites/{actuality}/modifier', [ActualityController::class, 'edit'])->name('actualities.edit');
    Route::put('/actualites/{actuality}', [ActualityController::class, 'update'])->name('actualities.update');
    Route::post('/actualites/{actuality}/publier', [ActualityController::class, 'publish'])->name('actualities.publish');
    Route::delete('/actualites/{actuality}', [ActualityController::class, 'destroy'])->name('actualities.destroy');

    Route::get('/utilisateurs', [UserController::class, 'index'])->name('users.index');
    Route::post('/utilisateurs/{user}/toggle-actif', [UserController::class, 'toggleActive'])->name('users.toggle');
    Route::post('/utilisateurs/{user}/toggle-suspension', [UserController::class, 'toggleSuspend'])->name('users.suspend');
    Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/utilisateurs/{user}/logs', [UserController::class, 'authLogs'])->name('users.logs');

    Route::get('/annonces', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/annonces/creer', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/annonces', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/annonces/{announcement}/modifier', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/annonces/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/annonces/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    Route::get('/modifications', [ModificationRequestController::class, 'index'])->name('modifications.index');
    Route::get('/modifications/{modificationRequest}', [ModificationRequestController::class, 'show'])->name('modifications.show');
    Route::post('/modifications/{modificationRequest}/approuver', [ModificationRequestController::class, 'approve'])->name('modifications.approve');
    Route::post('/modifications/{modificationRequest}/refuser', [ModificationRequestController::class, 'reject'])->name('modifications.reject');

    Route::get('/parametres', [SettingsController::class, 'index'])->name('settings');
    Route::put('/parametres/compte', [SettingsController::class, 'updateAccount'])->name('settings.account');
    Route::put('/parametres/mot-de-passe', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/parametres/notifications', [SettingsController::class, 'updateNotifications'])->middleware('admin.only')->name('settings.notifications');
});
