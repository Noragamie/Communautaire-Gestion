<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actuality;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use App\Services\CommuneContext;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $ids = CommuneContext::scopedCommuneIdsForQuery();
        /** @var User|null $user */
        $user = Auth::user();

        $profileQuery = Profile::query();
        if (CommuneContext::needsTerritorialScope()) {
            if ($ids === []) {
                $profileQuery->whereRaw('1 = 0');
            } else {
                $profileQuery->whereHas('user', fn ($q) => $q->whereIn('commune_id', $ids));
            }
        }

        $stats = [
            'total' => (clone $profileQuery)->count(),
            'pending' => (clone $profileQuery)->where('status', 'pending')->count(),
            'approved' => (clone $profileQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $profileQuery)->where('status', 'rejected')->count(),
            'suspended' => (clone $profileQuery)->where('status', 'suspended')->count(),
        ];

        $stats['total_delta'] = $this->monthOverMonthNewProfilesDelta($ids);

        $recentProfiles = Profile::with(['user', 'category'])
            ->when(CommuneContext::needsTerritorialScope() && $ids !== [], fn ($q) => $q->whereHas('user', fn ($u) => $u->whereIn('commune_id', $ids)))
            ->when(CommuneContext::needsTerritorialScope() && $ids === [], fn ($q) => $q->whereRaw('1 = 0'))
            ->latest()
            ->take(10)
            ->get();

        Carbon::setLocale('fr');

        $chartPayload = [
            'status' => [
                'labels' => ['En attente', 'Approuvés', 'Rejetés', 'Suspendus'],
                'data' => [
                    $stats['pending'],
                    $stats['approved'],
                    $stats['rejected'],
                    $stats['suspended'],
                ],
            ],
            'categories' => $this->profilesByCategory(clone $profileQuery),
            'activity' => $this->lastSixMonthsProfileCounts(clone $profileQuery),
            'content' => $this->lastSixMonthsAnnouncementsAndActualities($ids),
        ];

        $showCommuneComparison = $user && $user->isAdmin();
        if ($showCommuneComparison) {
            $chartPayload['communes'] = $this->approvedProfilesByManagedCommune($user);
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentProfiles',
            'chartPayload',
            'showCommuneComparison'
        ));
    }

    /**
     * @param  list<int>  $communeIds
     */
    private function monthOverMonthNewProfilesDelta(array $communeIds): ?int
    {
        if (! CommuneContext::needsTerritorialScope()) {
            return null;
        }
        if ($communeIds === []) {
            return null;
        }

        $base = Profile::query()->whereHas('user', fn ($q) => $q->whereIn('commune_id', $communeIds));

        $thisMonth = (clone $base)->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonth = (clone $base)->whereBetween('created_at', [
            now()->subMonthNoOverflow()->startOfMonth(),
            now()->subMonthNoOverflow()->endOfMonth(),
        ])->count();

        return $thisMonth - $lastMonth;
    }

    private function profilesByCategory($query): array
    {
        $rows = (clone $query)
            ->selectRaw('profiles.category_id, COUNT(*) as total')
            ->groupBy('profiles.category_id')
            ->get();

        $categoryIds = $rows->pluck('category_id')->filter()->unique()->values();
        $names = Category::whereIn('id', $categoryIds)->pluck('name', 'id');

        $pairs = [];
        foreach ($rows as $row) {
            $label = $row->category_id
                ? (string) ($names[$row->category_id] ?? 'Catégorie')
                : 'Non renseigné';
            $pairs[] = ['label' => $label, 'count' => (int) $row->total];
        }

        usort($pairs, fn ($a, $b) => $b['count'] <=> $a['count']);

        if ($pairs === []) {
            return ['labels' => ['Aucune donnée'], 'data' => [0]];
        }

        return [
            'labels' => array_column($pairs, 'label'),
            'data' => array_column($pairs, 'count'),
        ];
    }

    private function lastSixMonthsProfileCounts($query): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i)->startOfMonth();
            $labels[] = $m->translatedFormat('M Y');
            $data[] = (clone $query)
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * @param  list<int>  $communeIds
     */
    private function lastSixMonthsAnnouncementsAndActualities(array $communeIds): array
    {
        $labels = [];
        $announcements = [];
        $actualities = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i)->startOfMonth();
            $labels[] = $m->translatedFormat('M Y');

            if ($communeIds === []) {
                $announcements[] = 0;
                $actualities[] = 0;

                continue;
            }

            $announcements[] = Announcement::query()
                ->whereIn('commune_id', $communeIds)
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count();

            $actualities[] = Actuality::query()
                ->whereIn('commune_id', $communeIds)
                ->whereYear('created_at', $m->year)
                ->whereMonth('created_at', $m->month)
                ->count();
        }

        return compact('labels', 'announcements', 'actualities');
    }

    private function approvedProfilesByManagedCommune(User $user): array
    {
        $communes = $user->managedCommunes()->orderBy('name')->get();
        if ($communes->isEmpty()) {
            return ['labels' => [], 'data' => []];
        }

        $ids = $communes->pluck('id');

        $counts = Profile::query()
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->where('profiles.status', 'approved')
            ->whereIn('users.commune_id', $ids)
            ->selectRaw('users.commune_id as commune_id, COUNT(*) as c')
            ->groupBy('users.commune_id')
            ->pluck('c', 'commune_id');

        $labels = [];
        $data = [];
        foreach ($communes as $c) {
            $labels[] = $c->name;
            $data[] = (int) ($counts[$c->id] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
