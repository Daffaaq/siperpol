<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'nama_staff',
        'nama_panggilan_staff',
        'email_staff',
        'password_staff',
        'alamat_staff',
        'no_telepon_staff',
        'jenis_kelamin_staff',
        'tanggal_lahir_staff',
        'pendidikan_terakhir_staff',
        'status_kepegawaian_staff',
        'status_kepegawaian_lainnya',
        'users_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
