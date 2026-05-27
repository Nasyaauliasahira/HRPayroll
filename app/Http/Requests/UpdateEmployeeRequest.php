<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('employee')->id ?? null;
        return [
            'employee_code' => 'required|string|unique:employees,employee_code,' . $id,
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|string|max:20',
            'nik' => 'required|string|unique:employees,nik,' . $id,
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'join_date' => 'required|date',
            'employment_type' => 'required|in:full_time,contract,intern',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'base_salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
        ];
    }
}
