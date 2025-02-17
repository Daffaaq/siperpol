<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKepalaJurusanRequest extends FormRequest
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
            'nama_ketua_jurusan' => 'required|string',
            'nama_pendek_ketua_jurusan' => 'required|string',
            'nip_ketua_jurusan' => 'required|string|unique:ketua_jurusans,nip_ketua_jurusan',
            'email_ketua_jurusan' => 'required|email|unique:ketua_jurusans,email_ketua_jurusan',
            'password_ketua_jurusan' => 'required|string|min:8',
            'jurusans_id' => 'required|exists:jurusans,id',
        ];
    }
}
