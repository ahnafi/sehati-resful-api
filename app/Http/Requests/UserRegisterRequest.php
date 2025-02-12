<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
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
            "email" => "required|max:100|email|unique:users,email",
            "password" => ["required", Password::min(8)->max(255)->numbers()->mixedCase()],
            "name" => "required|string|min:3|max:100",
            "address" => "nullable|string|max:255"
        ];
    }
}
