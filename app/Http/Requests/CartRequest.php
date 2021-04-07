<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'qty' => 'required|integer|gt:0',
            'message' => 'nullable|string|max:255'
        ];

        if ($this->method() === 'PATCH') {
            $rules['product_id'] = str_replace('required', 'nullable', $rules['product_id']);
        }
        
        return $rules;
    }
}
