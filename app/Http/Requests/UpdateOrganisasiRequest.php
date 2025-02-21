<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganisasiRequest extends FormRequest
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
            'nama_organisasi_intra' => 'required|string|max:255',
            'kode_organisasi_intra' => 'required|string|max:255',
            'tipe_organisasi_intra' => 'required|string|in:jurusan,non-jurusan,lembaga-tinggi',
            'jurusans_id' => 'nullable|exists:jurusans,id',
            'is_active' => 'required|boolean',
            'nama_ketua_umum' => 'nullable|string|max:255',
            'email_ketua_umum' => 'nullable|email|max:255|unique:users,email,' . $this->route('organisasi'),
            'password_ketua_umum' => 'nullable|string|min:8',
        ];
    }
}
