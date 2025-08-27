<?php

namespace App\Exports;

use App\Models\IdCard;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IdCardsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $agencyId;
    protected $tgl;

    public function __construct($agencyId, $tgl)
    {
        $this->agencyId = $agencyId;
        $this->tgl = $tgl;
    }

    public function collection()
    {
        return IdCard::whereDate('created_at', $this->tgl)->where('agency_id', $this->agencyId)->get([
            'no1',
            'name1',
            'kelas1',
            'alamat1',
            'darah1',
            'agama1',
            'kelamin1',
            'nis1',
            'nisn1',
            'ttl1',
            'no2',
            'name2',
            'kelas2',
            'alamat2',
            'darah2',
            'agama2',
            'kelamin2',
            'nis2',
            'nisn2',
            'ttl2',
            'no3',
            'name3',
            'kelas3',
            'alamat3',
            'darah3',
            'agama3',
            'kelamin3',
            'nis3',
            'nisn3',
            'ttl3',
            'no4',
            'name4',
            'kelas4',
            'alamat4',
            'darah4',
            'agama4',
            'kelamin4',
            'nis4',
            'nisn4',
            'ttl4',
            'no5',
            'name5',
            'kelas5',
            'alamat5',
            'darah5',
            'agama5',
            'kelamin5',
            'nis5',
            'nisn5',
            'ttl5',
        ]);
    }

    /**
     * Heading di baris pertama file Excel
     */

    public function headings(): array
    {
        return [
            'No 1',
            'Nama 1',
            'Kelas 1',
            'Alamat 1',
            'Darah 1',
            'Agama 1',
            'Kelamin 1',
            'NIS 1',
            'NISN 1',
            'TTL 1',

            'No 2',
            'Nama 2',
            'Kelas 2',
            'Alamat 2',
            'Darah 2',
            'Agama 2',
            'Kelamin 2',
            'NIS 2',
            'NISN 2',
            'TTL 2',
            'No 3',
            'Nama 3',
            'Kelas 3',
            'Alamat 3',
            'Darah 3',
            'Agama 3',
            'Kelamin 3',
            'NIS 3',
            'NISN 3',
            'TTL 3',

            'No 4',
            'Nama 4',
            'Kelas 4',
            'Alamat 4',
            'Darah 4',
            'Agama 4',
            'Kelamin 4',
            'NIS 4',
            'NISN 4',
            'TTL 4',

            'No 5',
            'Nama 5',
            'Kelas 5',
            'Alamat 5',
            'Darah 5',
            'Agama 5',
            'Kelamin 5',
            'NIS 5',
            'NISN 5',
            'TTL 5',
        ];
    }
}
