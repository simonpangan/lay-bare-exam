<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => ['required', 'max:100'],
            'sku'         => ['required', 'max:50',
                Rule::when($this->isMethod('post'), function () {
                    return sprintf("unique:%s", (new Product())->getTable());
                }),
                Rule::when($this->isMethod('put'), function () {
                    return Rule::unique((new Product)->getTable())
                       ->ignore($this->route('product')->id);
                })
            ],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['required', 'max:2400'],
            'added_by'    => ['required', 'max:50'],
            'added_by'    => ['required', 'max:50'],
            'image' => [Rule::when($this->isMethod('post'), function () {
                return 'required';
            }), 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'new_image' => [Rule::when($this->isMethod('put'), function () {
                return 'required';
            }), 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        //if it is put/updated then we remove the new_image key
        //so we can use the validated() method in the controller
        //without conflicts in the image column in DB
        if ($this->isMethod('put')) {
            $validated['image'] = $validated['new_image'];
            unset($validated['new_image']);
        }

        return $validated;
    }

    public function authorize(): bool
    {
        return true;
    }
}
