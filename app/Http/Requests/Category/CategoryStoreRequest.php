<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
class CategoryStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:15|unique:categories',
            'description' => 'nullable|string|min:30|max:255',
            
        ];
    }





}
