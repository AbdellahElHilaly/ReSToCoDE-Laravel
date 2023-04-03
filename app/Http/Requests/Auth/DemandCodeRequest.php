<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
class DemandCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }


}
