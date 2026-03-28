<?php

namespace App\Providers;

use App\Models\ModificationRequest;
use App\Services\CommuneContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (! Auth::check() || ! Auth::user()->isBackoffice()) {
                return;
            }
            $user = Auth::user();
            $ids = CommuneContext::scopedCommuneIdsForQuery();
            $pending = $ids === []
                ? 0
                : ModificationRequest::query()
                    ->where('status', 'pending')
                    ->whereHas('profile.user', fn ($q) => $q->whereIn('commune_id', $ids))
                    ->count();
            $view->with([
                'adminLayoutPendingModifications' => $pending,
                'adminActiveCommuneModel' => CommuneContext::activeCommune(),
                'adminViewingAllManagedCommunes' => $user->isAdmin() && CommuneContext::isAdminViewingAllManagedCommunes(),
                'adminManagedCommunes' => $user->isAdmin()
                    ? $user->managedCommunes()->orderBy('name')->get()
                    : collect(),
            ]);
        });
    }
}
