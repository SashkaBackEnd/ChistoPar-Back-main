<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialistRequest extends FormRequest
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
            "user_id" => 'required|integer',
            "bath_serices_id" => 'integer',
            'name' => 'required|max:255',
            "description" => 'string|nullable',
            "media" => 'string|nullable',
            "courses" => 'string|nullable',
            "achievements" => 'string|nullable',
            'facebook'  => 'string|nullable',
            'vk'  => 'string|nullable',
            'instagram'  => 'string|nullable',
            'twitter'  => 'string|nullable',
            'phone'  => 'string|required',
            'email'  => 'string|required',
            'bath_id' => 'integer',
            'bath_category_id' => 'integer|required'
        ];
    }
}
