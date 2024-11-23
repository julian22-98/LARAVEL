<?php

namespace App\Http\Requests\Roles;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
    public function rules(Request $request):array
    {
        return [
            'id'=>'required|exists:roles,id|numeric',
            'nombre'=>['required','min:3','max:20','string',Rule::unique('roles','name')->ignore($request->id)],
            'titulo'=>['required','min:3','max:20','string',Rule::unique('roles','title')->ignore($request->id)],
            'habilidades'=>'required|array'
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(),422));
    }
}
