<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50',
                Rule::when($this->isMethod('post'), function () {
                    return 'unique:users';
                }),
                Rule::when($this->isMethod('put'), function () {
                    return Rule::unique((new User)->getTable())
                        ->ignore($this->route('user')->id);
                }),
            ],
            'first_name' => ['required', 'string', 'max:50'],
            'middle_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50',
                Rule::when($this->isMethod('post'), function () {
                    return 'unique:users';
                }),
                Rule::when($this->isMethod('put'), function () {
                    return Rule::unique((new User)->getTable())
                       ->ignore($this->route('user')->id);
                }),
            ],
            'password' => [
                Rule::when($this->isMethod('post'), function () {
                    return 'required';
                }),
                Rule::when($this->isMethod('put'), function () {
                    return 'optional';
                }),
                'string', Password::defaults()
            ],
        ];
    }
}
