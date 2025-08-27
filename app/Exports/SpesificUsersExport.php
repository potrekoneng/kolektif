<?php

namespace App\Exports;

use App\Models\IdCard;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpesificUsersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $agencyId;
    protected $tipe;

    public function __construct($agencyId, $tipe)
    {
        $this->agencyId = $agencyId;
        $this->tipe = $tipe;
    }

    public function collection()
    {
        if ($this->tipe == 'pending') {
            $users = User::where('agency_id', $this->agencyId)
                ->whereNull('photo')
                ->get([
                    'name',
                    'kelas',
                    'nisn',
                ]);
        } else {
            $users = User::where('agency_id', $this->agencyId)
                ->where('nisn', 'like', '8888%')
                ->get([
                    'name',
                    'kelas',
                    'nisn',
                ]);
        }

        // Tambahkan nomor urut menggunakan map
        return $users->values()->map(function ($user, $index) {
            return [
                'no' => $index + 1,
                'name' => $user->name,
                'kelas' => $user->kelas,
                'nisn' => $user->nisn,
            ];
        });
        // return User::where('agency_id', $this->agencyId)->where('photo', null)->get([
        //     'name',
        //     'kelas',
        //     'nisn',
        // ]);
    }

    /**
     * Heading di baris pertama file Excel
     */

    public function headings(): array
    {
        return [
            'No.',
            'Nama',
            'Kelas',
            'NISN',
        ];
    }
}
