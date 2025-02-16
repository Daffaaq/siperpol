<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuang extends Model
{
    use HasFactory;

    // Nama tabel (laravel akan otomatis menggunakan 'peminjaman_ruangs' sesuai dengan konvensi plural)
    protected $table = 'peminjaman_ruangs';

    // Kolom yang bisa diisi
    protected $fillable = [
        'ruangs_id',
        'mahasiswas_id',
        'tanggal_pinjam',
        'jam_pinjam',
        'jam_selesai',
        'keterangan',
        'organisasi_intras_id',
        'status',
    ];

    // Relasi dengan tabel Ruang
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruangs_id');
    }

    // Relasi dengan tabel Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswas_id');
    }

    // Relasi dengan tabel OrganisasiIntra
    public function organisasiIntra()
    {
        return $this->belongsTo(OrganisasiIntra::class, 'organisasi_intras_id');
    }
}
