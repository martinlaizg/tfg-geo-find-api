<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
 */

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('', ['uses' => 'HomeController@home']);
    $router->get('dashboard', ['uses' => 'HomeController@dashboard']);
    $router->post('login', ['uses' => 'LoginController@login']);

    // $router->get('users', ['uses' => 'UserController@getAll']);
    $router->post('users', ['uses' => 'UserController@create']);
    // $router->put('users', ['uses' => 'UserController@create']);
    // $router->delete('users', ['uses' => 'UserController@create']);

    $router->get('users/{id}', ['uses' => 'UserController@get']);
    // $router->put('users/{id}', ['uses' => 'UserController@update']);
    // $router->delete('users/{id}', ['uses' => 'UserController@delete']);

    $router->get('maps', ['uses' => 'MapController@getAll']);
    $router->post('maps', ['uses' => 'MapController@create']);
    // $router->put('maps', ['uses' => 'MapController@create']);
    // $router->delete('maps', ['uses' => 'MapController@create']);

    // $router->get('maps/{id}', ['uses' => 'MapController@get']);
    $router->put('maps/{id}', ['uses' => 'MapController@update']);
    // $router->delete('maps/{id}', ['uses' => 'MapController@delete']);

    $router->get('maps/{id}/locations', ['uses' => 'MapController@getLocations']);
    $router->post('maps/{id}/locations', ['uses' => 'MapController@createLocations']);
    $router->put('maps/{id}/locations', ['uses' => 'MapController@updateLocations']);
    // $router->delete('maps/{id}/locations', ['uses' => 'MapController@getLocations']);

    // $router->get('maps/{id}/locations/{locId}', ['uses' => 'MapController@getLocations']);
    // $router->put('maps/{id}/locations/{locId}', ['uses' => 'MapController@getLocations']);
    // $router->delete('maps/{id}/locations/{locId}', ['uses' => 'MapController@getLocations']);

    // Location endpoints
    // $router->get('locations', ['uses' => 'LocationController@getAll']);
    // $router->post('locations', ['uses' => 'LocationController@create']);
    // $router->get('locations/{id}', ['uses' => 'LocationController@get']);
    // $router->put('locations/{id}', ['uses' => 'LocationController@update']);
    // $router->delete('locations/{id}', ['uses' => 'LocationController@delete']);

    // Login

});
