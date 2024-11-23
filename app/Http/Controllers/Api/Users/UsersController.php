<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUsersRequest;
use App\Http\Requests\Users\UpdateUsersRequest;
use App\Http\Resources\Users\UsersCollection;
use App\Http\Resources\Users\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Bouncer;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:users-index')->only('index');
        $this->middleware('can:users-show')->only('show');
        $this->middleware('can:users-create')->only('store');
        $this->middleware('can:users-update')->only('update');
        $this->middleware('can:users-delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UsersCollection
     */
    public function index(Request $request): UsersCollection
    {
        return new UsersCollection(User::with('Roles','abilities')->orderBy('id','desc')->search($request->q)->paginate(8));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUsersRequest $request
     * @return JsonResponse
     */
    public function store(StoreUsersRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->nombre;
        $user->lastname = $request->apellido;
        $user->identification = $request->identificacion;
        $user->email = $request->correo;
        $user->active =$request->estado;
        $user->reset_password = $request->cambio_password;
        $user->password = bcrypt($request->password);
        $user->save();
        Bouncer::assign($request->rol)->to($user);

        return response()->json(['data'=>['success']],201);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UsersResource
     */
    public function show(User $user): UsersResource
    {
        return new UsersResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUsersRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUsersRequest $request, User $user): JsonResponse
    {
        $user->name = $request->nombre;
        $user->lastname = $request->apellido;
        $user->identification = $request->identificacion;
        $user->email = $request->correo;
        $user->active =$request->estado;
        $user->reset_password = $request->cambio_password;
        $user->password = bcrypt($request->password);
        $user->save();
        foreach ($user->roles as $role) {
            Bouncer::retract($role->name)->from($user);
        }
        Bouncer::assign($request->rol)->to($user);

        return response()->json(['data'=>['success']],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        $user->save();

        return response()->json(['data'=>['success']],204);

    }


    /**
     * Change stade active user.
     *
     * @param User $user
     * @return JsonResponse
     *
     */
    public function blockUser(User $user): JsonResponse
    {
        //invertir estado
        $user->active= !$user->active;
        $user->save();

        //eliminar todos los token del usuario
        foreach ($user->tokens as $token) {
            $token->delete();
        }

        return response()->json(['data'=>['success']],200);

    }

    /**
     * Remove tokens specified user.
     *
     * @param User $user
     * @return JsonResponse
     *
     */
    public function removeToken(User $user): JsonResponse
    {
        //eliminar todos los token del usuario
        foreach ($user->tokens as $token) {
            $token->delete();
        }

        return response()->json(['data'=>['success']],200);

    }
}
