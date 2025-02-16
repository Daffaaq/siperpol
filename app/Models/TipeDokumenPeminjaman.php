<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeDokumenPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'tipe_dokumen_peminjaman';

    protected $fillable = [
        'tipe_dokumen',
        'is_active',
    ];
}
