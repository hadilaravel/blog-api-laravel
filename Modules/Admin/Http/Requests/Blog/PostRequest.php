<?php

namespace Modules\Admin\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->id;
        if($this->isMethod('post')){
            return [
                'title' => 'required|max:120|min:2',
                'body' => 'required|min:5',
                'category_id' => 'required|min:1|max:10000000000|regex:/^[0-9]+$/u|exists:categories,id',
                'image' => 'required|image|mimes:png,jpg,jpeg,gif,webp',
                'status' => 'required|numeric|in:0,1',
                'commentable' => 'required|numeric|in:0,1',
            ];
        }
        else{
            return [
                'title' => ['required' , 'min:2' , 'max:190' , 'unique:posts,title,' .  $id ],
                'body' => 'required|min:5',
                'category_id' => 'required|min:1|max:10000000000|regex:/^[0-9]+$/u|exists:categories,id',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp',
                'status' => 'required|numeric|in:0,1',
                'commentable' => 'required|numeric|in:0,1',
            ];
        }
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
