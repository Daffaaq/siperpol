<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $id = $this->route('ketua-jurusan'); // Make sure this is correct, check the name of the route parameter
        // dd($id);
        return [
            'nama_ketua_jurusan' => 'required|string',
            'nama_pendek_ketua_jurusan' => 'required|string',
            'nip_ketua_jurusan' => 'required|string',
            'email_ketua_jurusan' => 'required|email',
            'password_ketua_jurusan' => 'nullable|string|min:8',
        ];
    }
}
