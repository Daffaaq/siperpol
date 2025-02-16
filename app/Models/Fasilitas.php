<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'fasilitas';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama_fasilitas',
    ];

    // Relasi Many-to-Many dengan Ruang
    public function ruangs()
    {
        return $this->belongsToMany(Ruang::class, 'fasilitas_ruangs', 'fasilitas_id', 'ruangs_id');
    }
}
