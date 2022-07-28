<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BathRequest extends FormRequest
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
            "category_id" => 'required|integer',
            "user_id" => 'required|integer',
            'parent_id' => 'integer|nullable',
            'name' => 'required|max:255',
            "description" => 'required',
            "media" => 'string|required',
            "operation_mode" => 'string|nullable',
            "phone" => 'string|required',
            "address" => 'string|required',
            "cash" => 'integer',
            "sale" => 'integer',
            "price" => 'integer',
            "pay_type" => 'string|nullable',
            'badge' => 'integer|nullable',
            'site' => 'string|nullable',
            'vk' => 'string|nullable',
            'facebook' => 'string|nullable',
            'instagram' => 'string|nullable',
            'twitter' => 'string|nullable',
            'fio' => 'string|nullable',
            'fullname' => 'string|nullable',
            'owner_email' => 'string|nullable',
            'owner_phone' => 'string|nullable',
            'manager_phone' => 'string|nullable',
            'manager_email' => 'string|nullable',
            'email' => 'string|nullable',
            'manager_name' => 'string|nullable',
            'avatar' => 'string|nullable',
            'coordinates' => 'string|nullable',
            'link' => 'string|nullable|unique:baths',
            'main_rank_name' => 'string|nullable',
            'visit_rule' => 'string|nullable',
            'map' => 'string|nullable',
        ];
    }
}
