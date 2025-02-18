<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',
                'reporting_manager_id' => 'nullable|integer',
                'aadhar' => 'nullable|string|max:20',
                'pan' => 'nullable|string|max:10',
                'dob' => 'nullable|date',
                'gender' => 'nullable|integer',
                'alternate_mobile' => 'nullable|string|max:15',
                'address1' => 'nullable|string|max:255',
                'address2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'pin_code' => 'nullable|string|max:10',
                'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
