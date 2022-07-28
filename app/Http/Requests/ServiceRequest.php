<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'bath_id' => 'required|integer',
            'name' => 'required|max:255',
            'description' => 'string|nullable',
            'price' => 'required|integer',
            'time' => 'required|integer',
            'type' => 'integer|nullable'
        ];
    }
}
