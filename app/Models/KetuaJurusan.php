<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetuaJurusan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan nama default yang diharapkan (plural dari nama model)
    protected $table = 'ketua_jurusans';

    // Tentukan kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_ketua_jurusan',
        'nama_pendek_ketua_jurusan',
        'nip_ketua_jurusan',
        'email_ketua_jurusan',
        'password_ketua_jurusan',
        'jurusans_id',  // Foreign key yang merujuk ke tabel 'jurusans'
        'users_id',
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
