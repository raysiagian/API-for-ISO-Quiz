<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'username' => 'required|string|max:255',
            'phoneNumber' => 'required|string',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'gender' => 'required|string',
        ];
    }
}
