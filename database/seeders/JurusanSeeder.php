<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data jurusan
        DB::table('jurusans')->insert([
            [
                'nama_jurusan' => 'Teknologi Informasi',
                'kode_jurusan' => 'TI',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Teknik Sipil',
                'kode_jurusan' => 'TS',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Teknik Mesin',
                'kode_jurusan' => 'TM',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Teknik Kimia',
                'kode_jurusan' => 'TEKKIM',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Akuntansi',
                'kode_jurusan' => 'AK',
                'is_active' => true,
            ],
            [
                'nama_jurusan' => 'Administrasi Niaga',
                'kode_jurusan' => 'AN',
                'is_active' => true,
            ],
        ]);
    }
}
