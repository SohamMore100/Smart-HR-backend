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
            'user_id' => 'nullable|integer',
            'ssc_schoole' => 'nullable|string',
            'ssc_per' => 'nullable|numeric|between:0,100',
            // 'ssc_passout_year' => 'nullableinteger',
            'ssc_board' => 'nullable|string',
            'hsc_school' => 'nullable|string',
            'hsc_per' => 'nullable|numeric|between:0,100',
            // 'hsc_passout_year' => 'nullableinteger',
            'hsc_board' => 'nullable|string',
            'hsc_stream' => 'nullable|string',
            'graduation_college' => 'nullable|string',
            'graduation_cgpa' => 'nullable|numeric|min:1',
            // 'graduation_start_year' => 'nullable|integer',
            // 'graduation_passout_year' => 'nullableinteger',
            'graduation_university' => 'nullable|string',
            'PG_college' => 'nullable|string',
            'pg_cgpa' => 'nullable|numeric|between:0,10',
            // 'pg_start_year' => 'nullable|integer',
            // 'pg_passout_year' => 'nullable|integer',
            'pg_university' => 'nullable|string',
            // 'doc_ssc' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5000',
            // 'doc_hsc' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5000',
            // 'doc_graduation' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5000',
            // 'doc_pg' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:5000',
        ];
    }
}
