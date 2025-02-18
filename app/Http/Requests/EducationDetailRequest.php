<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationDetailRequest extends FormRequest
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
            'ssc_schoole' => 'nullable|string',
            'ssc_per' => 'nullable|numeric|between:0,100',
            'ssc_passout_year' => 'nullable|digits:4|integer',
            'ssc_board' => 'nullable|string',
            'hsc_school' => 'nullable|string',
            'hsc_per' => 'nullable|numeric|between:0,100',
            'hsc_passout_year' => 'nullable|digits:4|integer',
            'hsc_board' => 'nullable|string',
            'hsc_stream' => 'nullable|string',
            'graduation_college' => 'nullable|string',
            'graduation_cgpa' => 'nullable|numeric|between:0,10',
            'graduation_start_year' => 'nullable|digits:4|integer',
            'graduation_passout_year' => 'nullable|digits:4|integer',
            'graduation_university' => 'nullable|string',
            'PG_college' => 'nullable|string',
            'pg_cgpa' => 'nullable|numeric|between:0,10',
            'pg_start_year' => 'nullable|digits:4|integer',
            'pg_passout_year' => 'nullable|digits:4|integer',
            'pg_university' => 'nullable|string',
            'doc_ssc' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'doc_hsc' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'doc_graduation' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'doc_pg' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
        ];
    }
}
