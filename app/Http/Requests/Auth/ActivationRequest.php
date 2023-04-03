<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
class ActivationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'left' => 'required|numeric|exists:tokens,id',
            'right' => 'required|numeric|digits:5',
        ];
    }


}
