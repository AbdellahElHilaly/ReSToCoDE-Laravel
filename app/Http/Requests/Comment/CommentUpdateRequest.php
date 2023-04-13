<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
class CommentUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' => 'required|string|min:5|max:500',
        ];
    }

}



