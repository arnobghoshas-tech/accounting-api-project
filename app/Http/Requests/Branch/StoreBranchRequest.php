<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'status' => 'boolean',
            'location' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Branch validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Company is required',
            'company_id.exists' => 'Company not found',
            'name.required' => 'Branch name is required',
            'name.string' => 'Branch name must be a string',
            'name.max' => 'Branch name may not be greater than 255 characters',
            'code.required' => 'Branch code is required',
            'code.string' => 'Branch code must be a string',
            'code.max' => 'Branch code may not be greater than 50 characters',
            'code.unique' => 'Branch code already exists',
            'email.email' => 'Email must be a valid email address',
            'status.boolean' => 'Status must be true or false',
        ];
    }
}
