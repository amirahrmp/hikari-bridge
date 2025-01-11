<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the employee is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        switch($this->method()){
            case 'POST': {
                return [
                    'nama' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'nik' => 'required|unique:employees',
                    'jabatan_id' => 'required',
                    'jenis_kelamin' => 'required',
                    'status' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'nama' => 'required',
                    'email' => ['required','unique:employees,email,' . $this->route()->employee->id],
                    'password' => 'required',
                    'nik' => ['required','unique:employees,nik,' . $this->route()->employee->id],
                    'jabatan_id' => 'required',
                    'jenis_kelamin' => 'required',
                    'status' => 'required'
                ];
            }
        }
    }
}
