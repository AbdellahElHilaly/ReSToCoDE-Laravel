<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;
class MenuStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:3|max:50',
            'description' => 'required|string|min:10|max:255',
            'quantity' => 'integer|min:1|max:120',
            'date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:today',
                'before_or_equal:' . date('Y-m-d', strtotime('+1 year')),
            ],
        ];


        if($this->timeLate()) {
            $rules['time'] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'time.required' => 'You can\'t add menu for today after 12 am you are late !',
        ];
    }

    private function timeLate()
    {
        $hourNow = date('H');
        $dateNow = date('Y-m-d');
        if($dateNow === $this->date )  return $hourNow > 12;
        return false;
    }

}





