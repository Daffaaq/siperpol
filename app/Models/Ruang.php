<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    // Nama tabel (jika tidak menggunakan nama default)
    protected $table = 'ruangs';

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_ruang',
        'kode_ruang',
        'is_active',
        'kapasitas_ruang',
        'tipe_ruang',
        'image',
        'jurusans_id',  // Foreign key ke tabel jurusans
    ];

    // Relasi dengan model Fasilitas (Many-to-Many)
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_ruangs', 'ruangs_id', 'fasilitas_id');
    }

    // Relasi dengan model Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusans_id');
    }

    // Relasi dengan tabel jadwal_tidak_tersedia
    public function jadwalTidakTersedia()
    {
        return $this->hasMany(JadwalTidakTersedia::class, 'ruangs_id');
    }
}
