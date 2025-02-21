<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisasiIntra extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan nama default yang diharapkan (plural dari nama model)
    protected $table = 'organisasi_intras';

    // Tentukan kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_organisasi_intra',
        'kode_organisasi_intra',
        'nama_ketua_umum',   // Added to fillable for mass assignment
        'email_ketua_umum',  // Added to fillable for mass assignment
        'password_ketua_umum', // Added to fillable for mass assignment
        'is_active',
        'tipe_organisasi_intra',
        'jurusans_id',  // Foreign key yang merujuk ke tabel 'jurusans'
        'users_id',     // Added users_id for the foreign key relationship
    ];

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
