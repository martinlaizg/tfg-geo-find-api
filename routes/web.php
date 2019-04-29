<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
 */

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('', ['uses' => 'HomeController@home']);
    $router->get('dashboard', ['uses' => 'HomeController@dashboard']);
    $router->post('login', ['uses' => 'UserController@login']);

    // $router->get('users', ['uses' => 'UserController@getAll']);
    $router->post('users', ['uses' => 'UserController@create']);
    // $router->put('users', ['uses' => 'UserController@create']);
    // $router->delete('users', ['uses' => 'UserController@create']);

    $router->get('users/{user_id}', ['uses' => 'UserController@get']);
    // $router->put('users/{user_id}', ['uses' => 'UserController@update']);
    // $router->delete('users/{user_id}', ['uses' => 'UserController@delete']);
    $router->get('users/{user_id}/tours/{tour_id}', ['uses' => 'UserController@getPlay']);
    $router->post('users/{user_id}/tours/{tour_id}', ['uses' => 'UserController@createPlay']);

    $router->get('tours', ['uses' => 'TourController@getAll']);
    $router->post('tours', ['uses' => 'TourController@create']);
    // $router->put('tours', ['uses' => 'TourController@create']);
    // $router->delete('tours', ['uses' => 'TourController@create']);

    $router->get('tours/{tour_id}', ['uses' => 'TourController@getSingleTour']);
    $router->put('tours/{tour_id}', ['uses' => 'TourController@update']);
    // $router->delete('tours/{tour_id}', ['uses' => 'TourController@delete']);

    $router->get('tours/{tour_id}/places', ['uses' => 'PlaceController@getByTour']);
    // $router->post('tours/{tour_id}/places', ['uses' => 'TourController@createPlaces']);
    $router->put('tours/{tour_id}/places', ['uses' => 'TourController@updatePlaces']);
    // $router->delete('tours/{tour_id}/places', ['uses' => 'TourController@getPlaces']);

    $router->get('tours/{tour_id}/places/{place_id}', ['uses' => 'PlaceController@getPlaceByTour']);
    // $router->put('tours/{tour_id}/places/{locId}', ['uses' => 'TourController@getPlaces']);
    // $router->delete('tours/{tour_id}/places/{locId}', ['uses' => 'TourController@getPlaces']);

    // Location endpoints
    // $router->get('places', ['uses' => 'PlaceController@getAll']);
    // $router->post('places', ['uses' => 'PlaceController@create']);
    // $router->get('places/{id}', ['uses' => 'PlaceController@get']);
    // $router->put('places/{id}', ['uses' => 'PlaceController@update']);
    // $router->delete('places/{id}', ['uses' => 'PlaceController@delete']);

    // Login

});
