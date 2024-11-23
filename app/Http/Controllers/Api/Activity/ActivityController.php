<?php

namespace App\Http\Controllers\Api\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{

    public function index():JsonResponse
    {
        $activity = Activity::paginate(10);
        return  response()->json(['data'=>$activity],200);
    }

}
