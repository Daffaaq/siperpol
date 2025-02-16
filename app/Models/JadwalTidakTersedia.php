<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTidakTersedia extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'jadwal_tidak_tersedia';

    // Kolom yang bisa diisi
    protected $fillable = [
        'ruangs_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
    ];

    // Relasi dengan model Ruang
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruangs_id');
    }
}
