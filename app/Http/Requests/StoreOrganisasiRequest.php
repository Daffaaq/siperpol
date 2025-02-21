<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganisasiRequest extends FormRequest
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
            'nama_organisasi_intra' => 'required|string',
            'kode_organisasi_intra' => 'required|string',
            'is_active' => 'required|in:1,0',
            'jurusans_id' => 'nullable|exists:jurusans,id',
            'tipe_organisasi_intra' => 'required|in:jurusan,non-jurusan,lembaga-tinggi',
            'nama_ketua_umum' => 'nullable|string',
            'email_ketua_umum' => 'nullable|email',
            'password_ketua_umum' => 'nullable|string|min:8',
            'roles' => 'nullable',
        ];
    }
}
