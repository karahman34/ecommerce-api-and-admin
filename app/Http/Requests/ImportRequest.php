<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
     * Show view.
     *
     * @return  bool
     */
    public function showView()
    {
        return $this->method() === 'GET';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->showView()) {
            return [];
        }

        return [
            'file' => 'required|file|mimes:xlsx,csv'
        ];
    }
}
