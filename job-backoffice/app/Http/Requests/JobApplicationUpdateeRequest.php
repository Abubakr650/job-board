<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationUpdateeRequest extends FormRequest
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
            'status' => 'bail|required|string|in:pending,accepted,rejected',
        ];
    }

    
    public function messages()
    {
        return [
            'status.in' => 'The status must be pending, accepted, or rejected.',
            'status.required' => 'The status field is required.',
            'status.string' => 'The status field must be a string.',
            'status.bail' => 'The status field is required.',
        ];
    } 
}
