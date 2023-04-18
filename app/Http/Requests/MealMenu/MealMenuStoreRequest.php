<?php

namespace App\Http\Requests\MealMenu;



use Illuminate\Foundation\Http\FormRequest;

class MealMenuStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return  [
            'menu_id' => 'required|exists:menus,id',
            'meal_id' => 'required|exists:meals,id',
            'quantity' => 'integer|min:1|max:10',
        ];
    }
}





