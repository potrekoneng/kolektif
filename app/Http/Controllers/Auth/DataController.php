<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Agency;
use App\Models\IdCard;
use App\Models\StudentCard;
use Illuminate\Http\Request;
use App\Exports\PendingPhoto;
use App\Exports\IdCardsExport;
use Illuminate\Support\Facades\DB;
use App\Exports\StudentCardsExport;
use App\Exports\SpesificUsersExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    function getDataAgency($id)
    {
        $getAgency = Agency::find($id);
        if ($getAgency->tipe === 'kartupelajar') {
            $result = [
                'agency_id' => $id,
                'data' => StudentCard::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->whereYear('created_at', Carbon::today()->year)
                    ->where('agency_id', $id)
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get()
            ];
        } else {
            $result = [
                'agency_id' => $id,
                'data' => IdCard::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->whereYear('created_at', Carbon::today()->year)
                    ->where('agency_id', $id)
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get()
            ];
        }

        return Inertia::render('auth/DataHasilPhoto', [
            'tableData' => $result
        ]);
    }

    public function exportData($idAgency, $tgl)
    {
        $getAgency = Agency::find($idAgency);

        if ($getAgency->tipe === 'kartupelajar') {
            return Excel::download(new StudentCardsExport($idAgency, $tgl), $getAgency->name . '_' . $tgl . '.xlsx');
        } else {
            return Excel::download(new IdCardsExport($idAgency, $tgl), $getAgency->name . '_' . $tgl . '.xlsx');
        }
    }

    public function exportSpesificData($idAgency, $tipe)
    {
        $getAgency = Agency::find($idAgency);
        if ($tipe == 'pending') {
            $file = 'belum foto';
        } else {
            $file = 'nisn by system';
        }

        if ($getAgency) {
            return Excel::download(new SpesificUsersExport($idAgency, $tipe), $getAgency->name . ' ' . $file . '.xlsx');
        } else {
            return 'Gagal';
        }
    }
}
