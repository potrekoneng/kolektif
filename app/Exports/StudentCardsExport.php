<?php

namespace App\Exports;

use App\Models\StudentCard;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentCardsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */ /**
     * Ambil data yang ingin diekspor
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
        return StudentCard::whereDate('created_at', $this->tgl)->where('agency_id', $this->agencyId)->get([
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
            'no6',
            'name6',
            'kelas6',
            'alamat6',
            'darah6',
            'agama6',
            'kelamin6',
            'nis6',
            'nisn6',
            'ttl6',
            'no7',
            'name7',
            'kelas7',
            'alamat7',
            'darah7',
            'agama7',
            'kelamin7',
            'nis7',
            'nisn7',
            'ttl7',
            'no8',
            'name8',
            'kelas8',
            'alamat8',
            'darah8',
            'agama8',
            'kelamin8',
            'nis8',
            'nisn8',
            'ttl8',
            'no9',
            'name9',
            'kelas9',
            'alamat9',
            'darah9',
            'agama9',
            'kelamin9',
            'nis9',
            'nisn9',
            'ttl9',
            'no10',
            'name10',
            'kelas10',
            'alamat10',
            'darah10',
            'agama10',
            'kelamin10',
            'nis10',
            'nisn10',
            'ttl10',
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

            'No 6',
            'Nama 6',
            'Kelas 6',
            'Alamat 6',
            'Darah 6',
            'Agama 6',
            'Kelamin 6',
            'NIS 6',
            'NISN 6',
            'TTL 6',

            'No 7',
            'Nama 7',
            'Kelas 7',
            'Alamat 7',
            'Darah 7',
            'Agama 7',
            'Kelamin 7',
            'NIS 7',
            'NISN 7',
            'TTL 7',

            'No 8',
            'Nama 8',
            'Kelas 8',
            'Alamat 8',
            'Darah 8',
            'Agama 8',
            'Kelamin 8',
            'NIS 8',
            'NISN 8',
            'TTL 8',

            'No 9',
            'Nama 9',
            'Kelas 9',
            'Alamat 9',
            'Darah 9',
            'Agama 9',
            'Kelamin 9',
            'NIS 9',
            'NISN 9',
            'TTL 9',

            'No 10',
            'Nama 10',
            'Kelas 10',
            'Alamat 10',
            'Darah 10',
            'Agama 10',
            'Kelamin 10',
            'NIS 10',
            'NISN 10',
            'TTL 10',
        ];
    }
}
