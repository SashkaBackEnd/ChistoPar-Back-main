<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            "bath_id" => 'integer',
            "user_id" => 'required|integer',
            "specialist_id" => 'integer',
            "bath_formats_ids" => ' string',
            "comment" => 'string',
            "duration" => 'required',
            "date_from" => 'required|string',
            "date_to" => 'required|string',
            "status" => 'integer',
            "price" => 'integer',
            "pay_type" => 'integer'
        ];
    }
}
