<?php

namespace App\Exports;

use App\Models\Profile;
use App\Services\CommuneContext;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfilesExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    public function query()
    {
        $q = Profile::approved()->with(['user', 'category']);

        if (! CommuneContext::needsTerritorialScope()) {
            return $q;
        }

        $ids = CommuneContext::scopedCommuneIdsForQuery();
        if ($ids === []) {
            return $q->whereRaw('1 = 0');
        }

        return $q->whereHas('user', fn ($u) => $u->whereIn('commune_id', $ids));
    }

    public function headings(): array
    {
        return ['Nom', 'Email', 'Catégorie', 'Localisation', 'Secteur', 'Niveau d\'étude', 'Téléphone', 'Site web', 'Date inscription'];
    }

    public function map($profile): array
    {
        return [
            $profile->user->name,
            $profile->user->email,
            $profile->category?->name ?? '',
            $profile->localisation,
            $profile->secteur_activite,
            $profile->niveau_etude,
            $profile->telephone,
            $profile->site_web,
            $profile->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
