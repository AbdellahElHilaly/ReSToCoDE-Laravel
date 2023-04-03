<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
class RessetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string|min:6|confirmed',
            'left' => 'required|numeric|exists:tokens,id',
            'right' => 'required|numeric|digits:5',
        ];
    }

}
