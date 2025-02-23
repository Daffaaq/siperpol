<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fasilitas')->insert([
            ['nama_fasilitas' => 'Projector'],
            ['nama_fasilitas' => 'Whiteboard'],
            ['nama_fasilitas' => 'WiFi'],
            ['nama_fasilitas' => 'Air Conditioner'],
            ['nama_fasilitas' => 'Computers'],
            ['nama_fasilitas' => 'Speaker System'],
            ['nama_fasilitas' => 'Smartboard'],
            ['nama_fasilitas' => 'Chairs'],
            ['nama_fasilitas' => 'Tables'],
            ['nama_fasilitas' => 'Ceiling Fan'],
            ['nama_fasilitas' => 'Power Outlets'],
            ['nama_fasilitas' => 'Overhead Projector'],
            ['nama_fasilitas' => 'Document Camera'],
            ['nama_fasilitas' => 'Classroom Lighting'],
            ['nama_fasilitas' => 'Mic'],
            ['nama_fasilitas' => 'Board Erasers'],
            ['nama_fasilitas' => 'Air Purifier'],
            ['nama_fasilitas' => 'Projection Screen'],
            ['nama_fasilitas' => 'Laser Pointer'],
        ]);
    }
}
