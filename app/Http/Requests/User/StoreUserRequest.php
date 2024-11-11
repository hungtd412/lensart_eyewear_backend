<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;

class StoreUserRequest extends FormRequest {
    use FailedValidationTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'username' => 'required|string|min:5|max:20|unique:users',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:11',
                'regex:/^(0[3|5|7|8|9])[0-9]{8,9}$/'
            ],
            'role_id' => 'integer|in:2,3'
        ];
    }
}
