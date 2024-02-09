<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:50',
                Rule::when($this->isMethod('post'), function () {
                    return sprintf("unique:%s", (new Category)->getTable());
                }),
                Rule::when($this->isMethod('put'), function () {
                    return Rule::unique((new Category)->getTable())
                       ->ignore($this->route('category')->id);
                }),
            ],
            'description' => ['required', 'max:2400'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
