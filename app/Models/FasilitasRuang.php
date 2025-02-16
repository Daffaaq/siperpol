<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasRuang extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_ruangs';

    protected $fillable = [
        'ruangs_id',
        'fasilitas_id',
    ];
}
