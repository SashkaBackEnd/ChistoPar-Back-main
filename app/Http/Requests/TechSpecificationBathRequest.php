<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TechSpecificationBathRequest extends FormRequest
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
            "tech_specification_id" => 'required|integer',
            "bath_id" => 'required|integer',
            'descrition' => 'required|max:255'
        ];
    }
}
