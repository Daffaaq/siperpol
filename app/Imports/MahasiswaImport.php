<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    protected $prodis_id;

    public function __construct($prodis_id)
    {
        $this->prodis_id = $prodis_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Ubah tanggal_lahir_dosen jika ada dalam format Excel
        $tanggalLahir = null;
        if (!empty($row['tanggal_lahir_mahasiswa'])) {
            // Cek apakah tanggal dalam format excel, jika iya, konversi menjadi format yang tepat
            $tanggalLahir = Date::excelToDateTimeObject($row['tanggal_lahir_mahasiswa'])->format('Y-m-d');
        }

        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $row['nama_pendek_mahasiswa'],
            'email' => $row['email_mahasiswa'],
            'password' => Hash::make($row['password_mahasiswa']),  // Hash password
        ]);

        // Assign role ke user
        $user->assignRole('mahasiswa');

        return new Mahasiswa([
            'nama_mahasiswa' => $row['nama_mahasiswa'],
            'nama_pendek_mahasiswa' => $row['nama_pendek_mahasiswa'],
            'email_mahasiswa' => $row['email_mahasiswa'],
            'password_mahasiswa' => Hash::make($row['password_mahasiswa']),
            'alamat_mahasiswa' => $row['alamat_mahasiswa'],
            'nim_mahasiswa' => $row['nim_mahasiswa'],
            'no_telepon_mahasiswa' => $row['no_telepon_mahasiswa'],
            'jenis_kelamin_mahasiswa' => $row['jenis_kelamin_mahasiswa'],
            'tanggal_lahir_mahasiswa' => $tanggalLahir,
            'users_id' => $user->id,
            'prodis_id' => $this->prodis_id,
        ]);
    }
}
