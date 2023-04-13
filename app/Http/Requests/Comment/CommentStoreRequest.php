<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
class CommentStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'body' => 'required|string|min:5|max:500',
            'menu_id' => 'required|exists:menus,id',
        ];
    }

}



