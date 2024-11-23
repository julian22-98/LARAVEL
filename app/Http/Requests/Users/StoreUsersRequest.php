<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'nombre'=>'required|min:3|max:50|string',
            'apellido'=>'required|min:3|max:20|string',
            'correo'=>'required|email|unique:users,email',
            'identificacion'=>'required|min:3|max:30|alpha_num|unique:users,identification',
            'estado'=>'required|boolean',
            'cambio_password'=>'required|boolean',
            'password'=>'required|min:6|confirmed',
            'rol'=>'required|exists:roles,id'
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(),422));
    }
}
