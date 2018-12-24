<?php

namespace App\Http\Controllers;

use Monolog\Logger;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function home(){
        return response()->json(['message' => 'Bienvenido a la API de GeoFind']);
    }

    public function routeNotFound(){
		return response()->json([
			'message' => 'Ruta no disponible'
		]);
	}

	public function dashboard(Request $request){
		$log = new Logger(__CLASS__);

		$log->debug($request->header('token'));

	}

}
