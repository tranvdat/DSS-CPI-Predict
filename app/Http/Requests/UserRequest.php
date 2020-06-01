<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'bail|required|max:255',
            'password' => 'bail|required|min:6|max:255',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Chưa nhập email',
            'email.max' => 'Email quá dài',
            'password.required' => 'Chưa nhập mật khẩu',
            'password.min' => 'Mật khẩu quá ngắn',
            'password.max' => 'Mật khẩu quá dài',
        ];
    }
}
