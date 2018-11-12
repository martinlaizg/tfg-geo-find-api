<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller {

	public function home()
    {
        return response()->json(['message'=>'Bienvenido a la API de GeoFind']);
    }

}
