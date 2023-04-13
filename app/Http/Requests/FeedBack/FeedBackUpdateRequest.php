<?php

namespace App\Http\Requests\FeedBack;

use Illuminate\Foundation\Http\FormRequest;
class FeedBackUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'body' => 'required|integer|min:1|max:5',
        ];
    }

    }



