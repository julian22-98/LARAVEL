<?php

namespace App\Http\Controllers\Api\Backup;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessBackup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{

    public function index():JsonResponse
    {
        try {
            ProcessBackup::dispatch()->delay(now()->addMinutes(1));
            return response()->json(['message'=>'Realización de backup en proceso,en unos minutos llegará un correo con el estado del proceso'],200);
        }   catch (\Exception $e){
            return response()->json(['message'=>'Algo salió mal'],400);

        }
       // Artisan::call('backup:run');

    }
}
