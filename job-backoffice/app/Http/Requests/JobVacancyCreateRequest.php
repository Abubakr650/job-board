<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobVacancyCreateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'required|numeric|max:99999999999|min:0',
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'jobCategoryId' => 'required|string|max:255',
            'companyId' => 'required|string|max:255',

        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title field must be a string.',
            'title.max' => 'The title field must not be greater than 255 characters.',
            'location.required' => 'The location field is required.',
            'location.string' => 'The location field must be a string.',
            'location.max' => 'The location field must not be greater than 255 characters.',
            'salary.required' => 'The salary field is required.',
            'salary.numeric' => 'The salary field must be a number.',
            'salary.max' => 'The salary field is over the limit.',
            'salary.min' => 'The salary field must not be less than 0.',
            'type.required' => 'The type field is required.',
            'type.string' => 'The type field must be a string.',
            'type.max' => 'The type field must not be greater than 255 characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description field must be a string.',
            'jobCategoryId.required' => 'The jobCategory field is required.',
            'jobCategoryId.string' => 'The jobCategory field must be a string.',
            'jobCategoryId.max' => 'The jobCategory field must not be greater than 255 characters.',
            'companyId.required' => 'The company field is required.',
            'companyId.string' => 'The company field must be a string.',
            'companyId.max' => 'The company field must not be greater than 255 characters.',
        ];
    } 
}
