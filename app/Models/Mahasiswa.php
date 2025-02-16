<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan nama default yang diharapkan (plural dari nama model)
    protected $table = 'mahasiswas';

    // Tentukan kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_mahasiswa',
        'email_mahasiswa',
        'password_mahasiswa',
        'alamat_mahasiswa',
        'nim_mahasiswa',
        'no_telepon_mahasiswa',
        'jenis_kelamin_mahasiswa',
        'tanggal_lahir_mahasiswa',
        'prodis_id',  // Foreign key ke tabel prodis
        'jurusans_id',  // Foreign key ke tabel jurusans
        'users_id',  // Foreign key ke tabel users
    ];

    // Relasi dengan model Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodis_id');
    }

    // Relasi dengan model Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusans_id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
