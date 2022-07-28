<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JournalRequest extends FormRequest
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
            "author_id" => 'required|integer',
            "journal_category_id" => 'required|integer',
            "bath_id" => 'integer',
            "category_id" => 'integer',
            "type" => 'integer',
            "place" => 'string',
            "format" => 'string',
            "contacts" => 'string',
            "date" => 'string',
            'title' => 'required|max:255',
            "description" => 'required',
            "media" => 'string',
            "views" => 'integer',
            "hashtags" => 'string'
        ];
    }
}
