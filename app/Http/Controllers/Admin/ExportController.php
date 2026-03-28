<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProfilesExport;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Services\CommuneContext;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ProfilesExport, 'profils_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPdf()
    {
        $ids = CommuneContext::scopedCommuneIdsForQuery();
        $profiles = Profile::approved()
            ->with(['user', 'category'])
            ->when(CommuneContext::needsTerritorialScope() && $ids !== [], fn ($q) => $q->whereHas('user', fn ($u) => $u->whereIn('commune_id', $ids)))
            ->when(CommuneContext::needsTerritorialScope() && $ids === [], fn ($q) => $q->whereRaw('1 = 0'))
            ->get();
        $pdf = Pdf::loadView('admin.exports.pdf', compact('profiles'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('profils_'.now()->format('Y-m-d').'.pdf');
    }
}
