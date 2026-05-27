<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
        $id = $this->route('department')->id ?? null;
        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'description' => 'nullable|string',
            'head_employee_id' => 'nullable|exists:employees,id',
            'is_active' => 'boolean',
        ];
    }
}
