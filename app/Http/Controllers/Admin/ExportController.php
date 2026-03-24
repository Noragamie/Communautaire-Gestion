<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProfilesExport;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new ProfilesExport(), 'profils_'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPdf()
    {
        $profiles = Profile::approved()->with(['user','category'])->get();
        $pdf = Pdf::loadView('admin.exports.pdf', compact('profiles'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('profils_'.now()->format('Y-m-d').'.pdf');
    }
}
