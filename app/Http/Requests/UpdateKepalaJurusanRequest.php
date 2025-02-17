<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKepalaJurusanRequest extends FormRequest
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
            'nip_ketua_jurusan' => 'required|string|' . Rule::unique('ketua_jurusans')->ignore($this->ketua_jurusan),
            'email_ketua_jurusan' => 'required|email|' . Rule::unique('ketua_jurusans')->ignore($this->ketua_jurusan),
            'password_ketua_jurusan' => 'nullable|string|min:8',
        ];
    }
}
