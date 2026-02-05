<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
            'company_id' => 'required_unless:role,superadmin|nullable|exists:companies,id',
            'branch_id' => 'required_unless:role,superadmin|nullable|exists:branches,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'status' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'User validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'role.required' => 'Role is required',
            'role.exists' => 'Role is invalid',
            'company_id.required_unless' => 'Company is required unless role is superadmin',
            'company_id.exists' => 'Company not found',
            'branch_id.required_unless' => 'Branch is required unless role is superadmin',
            'branch_id.exists' => 'Branch not found',
        ];
    }
}
