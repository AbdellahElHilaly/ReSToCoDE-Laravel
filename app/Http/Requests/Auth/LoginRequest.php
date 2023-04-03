<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:6',
        ];
    }

}
