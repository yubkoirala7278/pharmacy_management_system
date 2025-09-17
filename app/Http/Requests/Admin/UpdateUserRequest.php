<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id), // ignore current user
            ],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles'     => ['nullable', 'array'],
            'roles.*'   => ['exists:roles,id'],
        ];
    }
}
