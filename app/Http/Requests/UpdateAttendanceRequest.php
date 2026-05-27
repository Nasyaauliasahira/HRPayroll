<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
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
        return [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:Y-m-d H:i:s',
            'check_out' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:check_in',
            'work_hours' => 'nullable|numeric|min:0',
            'late_minutes' => 'nullable|integer|min:0',
            'overtime_minutes' => 'nullable|integer|min:0',
            'status' => 'required|in:present,absent,late,leave,holiday',
            'notes' => 'nullable|string',
        ];
    }
}
