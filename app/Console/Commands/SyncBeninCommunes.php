<?php

namespace App\Console\Commands;

use App\Services\CommuneSyncService;
use Illuminate\Console\Command;

class SyncBeninCommunes extends Command
{
    protected $signature = 'communes:sync {--url= : URL du JSON des communes} {--file= : Chemin vers un fichier JSON local}';

    protected $description = 'Importe ou met à jour les communes du Bénin (API ou fichier local)';

    public function handle(CommuneSyncService $service): int
    {
        $file = $this->option('file');
        if ($file) {
            $n = $service->syncFromLocalFile($file);
        } else {
            $n = $service->syncFromUrl($this->option('url') ?: null);
        }

        $this->info($n > 0 ? "{$n} communes synchronisées." : 'Aucune commune importée (vérifiez l’URL ou database/data/benin_communes.json).');

        return self::SUCCESS;
    }
}
