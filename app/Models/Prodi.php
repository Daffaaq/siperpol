<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak menggunakan nama default yang diharapkan (plural dari nama model)
    protected $table = 'prodis';

    // Tentukan kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'nama_prodi',
        'kode_prodi',
        'is_active',
        'jurusans_id',  // Foreign key untuk relasi dengan tabel 'jurusans'
    ];

    // Relasi dengan model Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusans_id');
    }
}
