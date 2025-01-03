<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FailedValidationTrait;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest {
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
        //unique:users,email,' . $this->route('id') => ignore if updated email is same with current email
        return [
            'password' => 'string|min:6',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'address' => 'string|max:255',
            'phone' => [
                'required',
                'string',
                'max:11',
                'regex:/^(0[3|5|7|8|9])[0-9]{8,9}$/'
            ],
            'status' => 'in:active,inactive',
            'role_id' => 'integer|in:2,3'
        ];
    }
}
