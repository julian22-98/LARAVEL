<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\loginRequest;
use App\Http\Resources\Users\UserDataResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /*
     * method for login users
     */
    public function login(loginRequest $request): JsonResponse
    {
        // obtener usuario
        $user = User::with('roles')->where('identification', $request->identificacion)->first();


        // verificar que existencia del usuario y contraseña si sea correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => ['message'=>'Credenciales incorrectos'],
            ],422);
        }

        //verificar que este activo
        if ($user->active===false) {
            return response()->json([
                'email' => ['message'=>'Usuario inactivo'],
            ],422);
        }

        //evitar multiples login
        foreach ($user->tokens as $token) {
            $token->delete();
        }

        //obtener habilidades y añadir habilidades básicas para el front
        $abilities = $user->getAbilities()->pluck('name')->push('404')->push('403')->push('500')->push('home')->push('perfil')->push('reset');

        //crear token
        $token = $user->createToken($request->device_name)->plainTextToken;

        // retornar información
        return response()->json([
            'access_token' => $token,
            'user' => new UserDataResource($user),
            'abilities' => $abilities,
        ]);
    }

    /*
     * method for logout users
     */

    public function logout(): JsonResponse
    {
        // obtener usuario
        $user = auth()->user();

        //eliminar todas los token que existan del usuario
        foreach ($user->tokens as $token) {
            $token->delete();
        }

        // retornar información
        return response()->json(['message'=>'user logout'], 200);
    }


}
