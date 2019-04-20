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

    $router->get('maps', ['uses' => 'TourController@getAll']);
    $router->post('maps', ['uses' => 'TourController@create']);
    // $router->put('maps', ['uses' => 'TourController@create']);
    // $router->delete('maps', ['uses' => 'TourController@create']);

    $router->get('maps/{id}', ['uses' => 'TourController@getSingleTour']);
    $router->put('maps/{id}', ['uses' => 'TourController@update']);
    // $router->delete('maps/{id}', ['uses' => 'TourController@delete']);

    $router->get('maps/{id}/locations', ['uses' => 'PlaceController@getByTour']);
    // $router->post('maps/{id}/locations', ['uses' => 'TourController@createLocations']);
    $router->put('maps/{id}/locations', ['uses' => 'TourController@updateLocations']);
    // $router->delete('maps/{id}/locations', ['uses' => 'TourController@getLocations']);

    $router->get('maps/{id}/locations/{place_id}', ['uses' => 'PlaceController@getPlaceByTour']);
    // $router->put('maps/{id}/locations/{locId}', ['uses' => 'TourController@getLocations']);
    // $router->delete('maps/{id}/locations/{locId}', ['uses' => 'TourController@getLocations']);

    // Location endpoints
    // $router->get('locations', ['uses' => 'PlaceController@getAll']);
    // $router->post('locations', ['uses' => 'PlaceController@create']);
    // $router->get('locations/{id}', ['uses' => 'PlaceController@get']);
    // $router->put('locations/{id}', ['uses' => 'PlaceController@update']);
    // $router->delete('locations/{id}', ['uses' => 'PlaceController@delete']);

    // Login

});
