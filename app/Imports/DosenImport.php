<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class DosenImport implements ToModel, WithHeadingRow
{
    protected $jurusan_id;

    public function __construct($jurusan_id)
    {
        $this->jurusan_id = $jurusan_id;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Ubah tanggal_lahir_dosen jika ada dalam format Excel
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir_dosen'])) {
                // Cek apakah tanggal dalam format excel, jika iya, konversi menjadi format yang tepat
                $tanggalLahir = Date::excelToDateTimeObject($row['tanggal_lahir_dosen'])->format('Y-m-d');
            }

            // Buat user terlebih dahulu
            $user = User::create([
                'name' => $row['nama_panggilan_dosen'],
                'email' => $row['email_dosen'],
                'password' => Hash::make($row['password_dosen']),  // Hash password
            ]);

            // Assign role ke user
            $user->assignRole('dosen');  // Pastikan role 'dosen' sudah ada di aplikasi Anda

            // Kemudian buat data dosen dan kaitkan dengan user yang baru saja dibuat
            return new Dosen([
                'nama_dosen' => $row['nama_dosen'],
                'nama_panggilan_dosen' => $row['nama_panggilan_dosen'],
                'nidn_dosen' => $row['nidn_dosen'],
                'nip_dosen' => !empty($row['nip_dosen']) ? $row['nip_dosen'] : null,
                'email_dosen' => $row['email_dosen'],
                'password_dosen' => Hash::make($row['password_dosen']),
                'alamat_dosen' => $row['alamat_dosen'],
                'no_telepon_dosen' => $row['no_telepon_dosen'],
                'jenis_kelamin_dosen' => $row['jenis_kelamin_dosen'],
                'tanggal_lahir_dosen' => $tanggalLahir,  // Gunakan tanggal yang sudah di-convert
                'pendidikan_terakhir_dosen' => $row['pendidikan_terakhir_dosen'],
                'status_kepegawaian_dosen' => $row['status_kepegawaian_dosen'],
                'status_kepegawaian_lainnya' => !empty($row['status_kepegawaian_lainnya']) ? $row['status_kepegawaian_lainnya'] : null,
                'users_id' => $user->id,  // Kaitkan dengan user yang baru dibuat
                'jurusans_id' => $this->jurusan_id,  // Jurusan diambil dari parameter constructor
            ]);
        } catch (\Exception $e) {
            // Jika ada error dalam pembuatan user atau dosen, bisa log error atau menampilkan pesan
            Log::error('Error on importing dosen: ' . $e->getMessage());
            return null; // Agar baris ini tidak disimpan jika terjadi kesalahan
        }
    }
}
