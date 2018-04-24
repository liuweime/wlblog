<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            //
            'comment_id' => 'bail|integer',
            'article_id' => 'bail|required|integer',
            'nickname' => 'bail|required|string|max:20',
            'email' => 'bail|required|string|email',
            'content' => 'bail|required|string',
        ];
    }

}
