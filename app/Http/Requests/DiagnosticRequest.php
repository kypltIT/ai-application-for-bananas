<?php
// Created a new Request class for validating diagnostic inputs

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosticRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust according to your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prompt' => 'sometimes|string',
            '*' => 'required', // Require at least some data
        ];
    }
}
