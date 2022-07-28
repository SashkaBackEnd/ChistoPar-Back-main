<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaRequest extends FormRequest
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
            'bath_id' => 'integer',
            'journal_id' => 'integer',
            'name' => 'string|required', 
            'phone' => 'string|required', 
            'message' => 'string',
            'viewed' => 'boolean'
        ];
    }
}
