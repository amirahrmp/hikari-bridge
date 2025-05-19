<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationProgramHkcwRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
{
    return [
        'nama_kegiatan' => 'required|string|max:255',
        'full_name' => 'required|string|max:255',
        'nama_panggilan' => 'required|string|max:255',
        'parent_name' => 'required|string|max:255',
        'whatsapp_number' => 'required|string|max:15',
        'address' => 'required|string',
        'kelas' => 'required|string|max:255',
        'tipe' => 'required|string|max:255',
        'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ];
}
}
