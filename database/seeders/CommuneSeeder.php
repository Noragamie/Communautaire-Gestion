<?php

namespace Database\Seeders;

use App\Models\Actuality;
use App\Models\Announcement;
use App\Models\Commune;
use App\Models\User;
use App\Services\CommuneSyncService;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        app(CommuneSyncService::class)->syncFromLocalFile();

        $fallback = Commune::query()->orderBy('id')->value('id');
        if (! $fallback) {
            return;
        }

        Announcement::query()->whereNull('commune_id')->chunkById(50, function ($rows) use ($fallback) {
            foreach ($rows as $a) {
                $cid = User::query()->whereKey($a->user_id)->value('commune_id') ?? $fallback;
                $a->update(['commune_id' => $cid]);
            }
        });

        Actuality::query()->whereNull('commune_id')->chunkById(50, function ($rows) use ($fallback) {
            foreach ($rows as $row) {
                $cid = User::query()->whereKey($row->user_id)->value('commune_id') ?? $fallback;
                $row->update(['commune_id' => $cid]);
            }
        });
    }
}
