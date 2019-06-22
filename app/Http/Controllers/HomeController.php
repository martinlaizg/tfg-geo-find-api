<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    public function home()
    {
        return redirect()->to('api');
    }

    public function apiHome()
    {
        return response()->json(['message' => 'Bienvenido a la API de GeoFind']);
    }

    public function routeNotFound()
    {
        return response()->json([
            'message' => 'Ruta no disponible',
        ]);
    }
}
