<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Hash;
use Throwable;

class UserImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user = User::where('empid', $row['empid'])->first();

        if ($user) {
            $user->update([
                'firstname'     => $row['firstname'],
                'lastname'     => $row['lastname'],
                'name'     => $row['firstname'].' '.$row['lastname'],
                'email'    => $row['email'],
                'password' => Hash::make($row['password']),
                'dashboard' => ($row['role'] == 'superadmin') ? 'Admin' : 'Employee',
            ]);
        } else {
            $user = new User([
                'firstname'     => $row['firstname'],
                'lastname'     => $row['lastname'],
                'name'     => $row['firstname'].' '.$row['lastname'],
                'email'    => $row['email'],
                'empid'    => $row['empid'],
                'password' => Hash::make($row['password']),
                'status' => '1',
                'dashboard' => ($row['role'] == 'superadmin') ? 'Admin' : 'Employee',
                'verified' => '1',
                'darkmode' => setting('DARK_MODE')
            ]);
        }

        $user->assignRole($row['role']);

        return $user;
    }

    public function rules(): array
    {
        return  [
            '*.firstname' => ['required','alpha_num'],
            '*.lastname' => ['required','alpha_num'],
            '*.email' => ['required','string'],
            '*.password' => ['required'],
            '*.empid' => ['required'],
            '*.role' => ['required'],
        ];
    }
}
