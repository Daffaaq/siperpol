<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_mahasiswa' => 'required|string|max:255',
            'nama_pendek_mahasiswa' => 'required|string|max:255',
            'email_mahasiswa' => 'required|email|unique:mahasiswas,email_mahasiswa', // Validasi email unik
            'nim_mahasiswa' => 'required|string|max:20|unique:mahasiswas,nim_mahasiswa', // Validasi NIM unik
            'password_mahasiswa' => 'required|string|min:8',
            'alamat_mahasiswa' => 'nullable|string|max:500', // Alamat bersifat opsional
            'no_telepon_mahasiswa' => 'nullable|string|max:15', // No Telepon bersifat opsional
            'jenis_kelamin_mahasiswa' => 'required|in:L,P', // Validasi jenis kelamin
            'tanggal_lahir_mahasiswa' => 'required|date',
            'prodis_id' => 'required|exists:prodis,id', // Validasi prodi_id harus ada di tabel prodis
        ];
    }
}
