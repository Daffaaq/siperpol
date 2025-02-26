<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data prodi sesuai dengan jurusannya
        DB::table('prodis')->insert([
            // Teknologi Informasi
            [
                'nama_prodi' => 'Sistem Informasi',
                'kode_prodi' => 'SI',
                'jurusans_id' => 1,  // ID for Teknologi Informasi
                'is_active' => true,
            ],
            [
                'nama_prodi' => 'Informatika',
                'kode_prodi' => 'IF',
                'jurusans_id' => 1,  // ID for Teknologi Informasi
                'is_active' => true,
            ],

            // Teknik Sipil
            [
                'nama_prodi' => 'Teknik Sipil',
                'kode_prodi' => 'TSI',
                'jurusans_id' => 2,  // ID for Teknik Sipil
                'is_active' => true,
            ],

            // Teknik Mesin
            [
                'nama_prodi' => 'Teknik Mesin',
                'kode_prodi' => 'TM',
                'jurusans_id' => 3,  // ID for Teknik Mesin
                'is_active' => true,
            ],

            // Teknik Kimia
            [
                'nama_prodi' => 'Teknik Kimia',
                'kode_prodi' => 'TK',
                'jurusans_id' => 4,  // ID for Teknik Kimia
                'is_active' => true,
            ],

            // Akuntansi
            [
                'nama_prodi' => 'Akuntansi',
                'kode_prodi' => 'AK',
                'jurusans_id' => 5,  // ID for Akuntansi
                'is_active' => true,
            ],

            // Administrasi Niaga
            [
                'nama_prodi' => 'Manajemen Bisnis',
                'kode_prodi' => 'MB',
                'jurusans_id' => 6,  // ID for Administrasi Niaga
                'is_active' => true,
            ],
        ]);
    }
}
