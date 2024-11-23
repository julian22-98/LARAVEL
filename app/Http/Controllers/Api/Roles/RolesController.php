<?php

namespace App\Http\Controllers\Api\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\Roles\RolesCollection;
use App\Http\Resources\Roles\RolesResource;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Role;
use Illuminate\Http\JsonResponse;
use Bouncer;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:roles-index')->only('index');
        $this->middleware('can:roles-show')->only('show');
        $this->middleware('can:roles-create')->only('store');
        $this->middleware('can:roles-update')->only('update');
        $this->middleware('can:roles-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new RolesCollection(Role::with('abilities')->orderBy('id','desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        //creo un nuevo rol con bouncer
        $role = Bouncer::role()->create([
            'name' => $request->nombre,
            'title' => $request->titulo,
        ]);

        //creo relación con las habilidades
        foreach ($request->habilidades as $habilidad){
            Bouncer::allow($role->name)->to($habilidad);
        }

        //retorno rol creado
        return response()->json(new RolesResource($role),201);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return JsonResponse
     */
    public function show(Role $role)
    {
        //retorno información del rol consultado
        return new RolesResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role):JsonResponse
    {
        //actualizo datos del rol
        $role->title = $request->titulo;
        $role->name = $request->nombre;
        $role->save();

        // elimino relaciones con habilidades
        foreach ($role->abilities as $ability){
            Bouncer::disallow($role->name)->to($ability->name);
        }

        //recreo nuevamente las relaciones con habilidades
        foreach ($request->habilidades as $habilidad){
            Bouncer::allow($role->name)->to($habilidad);
        }

        // retorno el rol actualizado
        return response()->json(['data'=>$role],200) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role):JsonResponse
    {
        $role->delete();
        $role->save();

        return response()->json(['data'=>''],204);
    }
}
