<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPeminjamanRuang extends Model
{
    use HasFactory;

    protected $table = 'dokumen_peminjaman_ruangs';

    protected $fillable = [
        'tipe_dokumen_peminjaman_id',
        'peminjaman_ruangs_id',
        'nama_dokumen',
        'dokumen',
        'status1',
        'status2',
        'status3',
        'status4',
    ];

    public function tipe_dokumen_peminjamen()
    {
        return $this->belongsTo(TipeDokumenPeminjaman::class);
    }

    public function peminjaman_ruangs()
    {
        return $this->belongsTo(PeminjamanRuang::class);
    }
}
