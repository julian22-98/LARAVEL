<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateUsersRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request  $request):array
    {
        return [
            'id'=>'required|exists:users,id|numeric',
            'nombre'=>'required|min:3|max:50|string',
            'apellido'=>'required|min:3|max:20|string',
            'correo'=>['required','email',Rule::unique('users','email')->ignore($request->id)],
            'identificacion'=>['required','min:3','max:30','alpha_num',Rule::unique('users','identification')->ignore($request->id)],
            'estado'=>'required|boolean',
            'cambio_password'=>'required|boolean',
            'password'=>'nullable|min:6|confirmed',
            'rol'=>'required|exists:roles,id'
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(),422));
    }
}
