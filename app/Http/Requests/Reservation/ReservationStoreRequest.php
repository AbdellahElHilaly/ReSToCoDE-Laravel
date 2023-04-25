<?php

namespace App\Http\Requests\Reservation;



use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return  [
            'menu_id' => 'required|exists:menus,id',
        ];
    }
}





