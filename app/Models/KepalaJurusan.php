<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaJurusan extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan nama default yang diharapkan (plural dari nama model)
    protected $table = 'kepala_jurusans';

    // Tentukan kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_kepala_jurusan',
        'email_kepala_jurusan',
        'password_kepala_jurusan',
        'jurusans_id',  // Foreign key yang merujuk ke tabel 'jurusans'
    ];

    // Relasi dengan model Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusans_id');
    }
}
