<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SinginRequest extends FormRequest
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
            'name' => 'bail|required|min:2|max:255',
            'email' => 'bail|required|unique:users|max:255',
            'password' => 'bail|required|min:6|max:255',
            'confirm_password' => 'bail|required|same:password',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Chưa nhập tên',
            'name.min' => 'Tên quá ngắn',
            'name.max' => 'Tên quá dài',
            'email.required' => 'Chưa nhập email',
            'email.unique' => 'Email bị trùng',
            'email.max' => 'Email quá dài',
            'password.required' => 'Chưa nhập mật khẩu',
            'password.min' => 'Mật khẩu quá ngắn',
            'password.max' => 'Mật khẩu quá dài',
            'confirm_password.required' => 'Chưa xác nhận mật khẩu',
            'confirm_password.same' => 'Mật khẩu không khớp'
        ];
    }
}
