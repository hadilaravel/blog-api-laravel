<?php

namespace Modules\Admin\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'keywords' => 'required',
            'description' => 'required',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp,svg',
            'logo_footer' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp,svg',
            'logo_header' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp,svg',
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
