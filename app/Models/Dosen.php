<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';

    protected $fillable = [
        'id',
        'nama_dosen',
        'nidn_dosen',
        'nip_dosen',
        'email_dosen',
        'password_dosen',
        'alamat_dosen',
        'no_telepon_dosen',
        'jenis_kelamin_dosen',
        'tanggal_lahir_dosen',
        'pendidikan_terakhir_dosen',
        'status_kepegawaian_dosen',
        'status_kepegawaian_lainnya',
        'users_id',
    ];

    // Model Dosen
    public function setPasswordDosenAttribute($value)
    {
        // Selalu hash nilai password sebelum disimpan
        $this->attributes['password_dosen'] = Hash::make($value);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
