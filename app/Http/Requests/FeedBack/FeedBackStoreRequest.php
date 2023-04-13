<?php

namespace App\Http\Requests\FeedBack;

use Illuminate\Foundation\Http\FormRequest;
class FeedBackStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'body' => 'required|integer|min:1|max:5',
            'menu_id' => 'required|exists:menus,id',
        ];
    }

}



