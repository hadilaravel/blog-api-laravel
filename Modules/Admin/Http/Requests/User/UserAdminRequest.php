<?php

namespace Modules\Admin\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->isMethod('post')) {
            return [
                'name' => 'required',
                'username' =>  'required|max:120|unique:users,name|min:5|regex:/^[a-zA-Z0-9]+$/u',
                'password' => ['required', 'unique:users', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'activation' => 'required|numeric|in:0,1',
                'profile' => 'required|image|mimes:jpg,png,jpeg,webp',
            ];
        }else{
            return [
                'name' => 'required',
                'username' =>  'required|max:120|min:5|regex:/^[a-zA-Z0-9]+$/u|unique:users,id, ' . request()->id,
                'password' => ['nullable', 'unique:users', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                'activation' => 'required|numeric|in:0,1',
                'profile' => 'nullable|image|mimes:jpg,png,jpeg,webp',
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
