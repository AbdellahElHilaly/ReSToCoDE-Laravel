<?php

namespace App\Http\Requests\Meal;

use Illuminate\Foundation\Http\FormRequest;
class MealStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name' => 'required|string|min:3|max:50|unique:meals',
            'description' => 'required|string|min:10|max:255',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

}



