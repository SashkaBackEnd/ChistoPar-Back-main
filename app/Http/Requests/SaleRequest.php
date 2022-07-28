<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
        return [
            'bath_id' => 'integer|required',
            'name' => 'string|required', 
            'date_start' => 'string|required',
            'date_end' => 'string|required', 
            'image' => 'string|required'
        ];
    }
}
