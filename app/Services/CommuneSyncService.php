<?php

namespace App\Services;

use App\Models\Commune;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CommuneSyncService
{
    public function syncFromUrl(?string $url = null): int
    {
        $url = $url ?: config('communes.sync_url');

        if (! $url) {
            return $this->syncFromLocalFile();
        }

        try {
            $response = Http::timeout(60)->acceptJson()->get($url);
            if (! $response->successful()) {
                Log::warning('communes.sync.http_failed', ['url' => $url, 'status' => $response->status()]);

                return $this->syncFromLocalFile();
            }

            $data = $response->json();
            $count = $this->importPayload($data);
            if ($count > 0) {
                return $count;
            }
        } catch (\Throwable $e) {
            Log::warning('communes.sync.exception', ['message' => $e->getMessage()]);
        }

        return $this->syncFromLocalFile();
    }

    public function syncFromLocalFile(?string $path = null): int
    {
        $path = $path ?: database_path('data/benin_communes.json');
        if (! is_readable($path)) {
            return 0;
        }

        $json = json_decode((string) file_get_contents($path), true);
        if (! is_array($json)) {
            return 0;
        }

        return $this->importPayload($json);
    }

    public function importPayload(mixed $data): int
    {
        $rows = $this->normalizeRows($data);
        $n = 0;
        foreach ($rows as $row) {
            $name = $row['name'] ?? null;
            if (! $name) {
                continue;
            }
            $dept = $row['department_name'] ?? $row['department'] ?? null;
            $ext = $row['external_id'] ?? $row['id'] ?? $row['code'] ?? null;
            $ext = $ext !== null && $ext !== '' ? (string) $ext : null;

            if ($ext) {
                Commune::updateOrCreate(
                    ['external_id' => $ext],
                    ['name' => $name, 'department_name' => $dept]
                );
            } else {
                Commune::updateOrCreate(
                    ['name' => $name, 'department_name' => $dept],
                    ['external_id' => null]
                );
            }
            $n++;
        }

        return $n;
    }

    private function normalizeRows(mixed $data): array
    {
        if (! is_array($data)) {
            return [];
        }

        if (isset($data['data']) && is_array($data['data'])) {
            $data = $data['data'];
        }
        if (isset($data['communes']) && is_array($data['communes'])) {
            $data = $data['communes'];
        }

        if ($data !== [] && ! Arr::isList($data)) {
            $data = [$data];
        }

        $out = [];
        foreach ($data as $item) {
            if (! is_array($item)) {
                continue;
            }
            $name = $item['name'] ?? $item['nom'] ?? $item['libelle'] ?? $item['title'] ?? null;
            $dept = $item['department_name'] ?? $item['departement'] ?? $item['department'] ?? $item['département'] ?? null;
            $ext = $item['external_id'] ?? $item['id'] ?? $item['code'] ?? $item['insee'] ?? null;
            if ($name) {
                $out[] = [
                    'name' => is_string($name) ? trim($name) : $name,
                    'department_name' => is_string($dept) ? trim($dept) : $dept,
                    'external_id' => $ext,
                ];
            }
        }

        return $out;
    }
}
