<?php

namespace App\Http\Controllers\Api\Abilities;

use App\Http\Controllers\Controller;
use App\Http\Resources\Abilities\AbilitiesCollection;
use Illuminate\Http\Request;
use Silber\Bouncer\Database\Ability;
use \Illuminate\Http\JsonResponse;
use Silber\Bouncer\Database\Role;

class AbilitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:roles-create')->only('index');
        $this->middleware('can:roles-update')->only('index');
    }

    public function index():JsonResponse
    {
        $abilities = new AbilitiesCollection(Ability::All());
        return response()->json(['data'=>$abilities],200);
    }
}
