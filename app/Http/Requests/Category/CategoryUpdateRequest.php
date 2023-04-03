<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
class CategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:15',
            'description' => 'nullable|string|min:30|max:255',
            // 'user_id' => 'required|integer',
            // 'last_user_updated_id' => 'required|integer',
        ];
    }





}
