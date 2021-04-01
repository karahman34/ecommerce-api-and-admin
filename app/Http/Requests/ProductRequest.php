<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'category_id' => 'required|integer|gte:1',
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|gte:1',
            'price' => 'required|integer|gte:500',
            'description' => 'required|string|max:255',
            'images.*' => 'file|mimes:png,jpg,jpeg,bmp|max:4096'
        ];

        if (strtolower($this->method()) === 'post') {
            $rules['images'] = 'required';
        }

        return $rules;
    }
}
