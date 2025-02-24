<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRuangRequest extends FormRequest
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
            'nama_ruang' => 'required|string',
            'kode_ruang' => 'required|string|' . rule::unique('ruangs')->ignore($this->ruang),
            'is_active' => 'required|in:1,0',
            'kapasitas_ruang' => 'required|integer',
            'tipe_ruang' => 'required|in:laboratorium,class,auditorium,meeting',
            'jurusans_id' => 'required|exists:jurusans,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fasilitas_id' => 'nullable|array|min:1',
            'fasilitas_id.*' => 'exists:fasilitas,id',
        ];
    }
}
