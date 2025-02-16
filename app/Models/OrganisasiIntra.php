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
        'is_active',
        'tipe_organisasi_intra',
        'jurusans_id',  // Foreign key yang merujuk ke tabel 'jurusans'
    ];

    // Relasi dengan model Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusans_id');
    }
}
