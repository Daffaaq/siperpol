<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMahasiswaRequest extends FormRequest
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
            'email_mahasiswa' => 'required|email|' . Rule::unique('mahasiswas')->ignore($this->mahasiswa),
            'nim_mahasiswa' => 'required|string|max:20|' . Rule::unique('mahasiswas')->ignore($this->mahasiswa),
            'password_mahasiswa' => 'nullable|string|min:8', // Password is optional for update
            'alamat_mahasiswa' => 'nullable|string|max:500',
            'no_telepon_mahasiswa' => 'nullable|string|max:15|regex:/^[0-9]+$/',
            'jenis_kelamin_mahasiswa' => 'required|in:L,P',
            'tanggal_lahir_mahasiswa' => 'required|date',
            'prodis_id' => 'required|exists:prodis,id',
        ];
    }
}
