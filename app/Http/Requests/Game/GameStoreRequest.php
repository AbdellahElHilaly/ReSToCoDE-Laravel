<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
class GameStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:50|unique:games',
            'description' => 'required|string|min:10|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }





}
