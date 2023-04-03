<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
class PermissionStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'pcontroller_id' => 'required|integer|exists:pcontrollers,id',
            'pmethode_id.*' => 'required|distinct|exists:pmethodes,id'
        ];
    }





}
