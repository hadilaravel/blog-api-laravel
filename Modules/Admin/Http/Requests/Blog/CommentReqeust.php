<?php

namespace Modules\Admin\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class CommentReqeust extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string|min:3',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
