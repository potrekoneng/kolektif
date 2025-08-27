<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UsersImport implements ToModel, WithHeadingRow
{
    protected $agencyId;
    public function __construct($agencyId)
    {
        $this->agencyId = $agencyId;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'username'     => $row['username'],
            'role_id'    => '2',
            'name'    => $row['name'],
            'agency_id'    => $this->agencyId,
            'alamat'    => $row['alamat'],
            'darah'    => $row['darah'],
            'kelas'    => $row['kelas'],
            'agama'    => $row['agama'],
            'kelamin'    => $row['kelamin'],
            'nis'    => $row['nis'],
            'nisn'    => $row['nisn'],
            // 'tgl_lahir'    => Date::excelToDateTimeObject($row['tgl_lahir']),
            'tmp_lahir'    => $row['tmp_lahir'],
            'email'    => rand() . '@' . rand() . '.com',
            'password' => Hash::make('password'),
            // 'password' => bcrypt('password'),
        ]);
    }
}
