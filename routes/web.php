<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
 */

$router->get('', ['uses' => 'HomeController@home']);

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('', ['uses' => 'HomeController@apiHome']);

    $router->post('login', ['uses' => 'UserController@login']);
    $router->post('users', ['uses' => 'UserController@create']);
    $router->get('tours', ['uses' => 'TourController@getAll']);

    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {

        $router->get('users/{user_id}', ['uses' => 'UserController@get']);
        $router->put('users/{user_id}', ['uses' => 'UserController@update']);

        $router->post('tours', ['uses' => 'TourController@create']);

        $router->get('tours/{tour_id}', ['uses' => 'TourController@getSingleTour']);
        $router->put('tours/{tour_id}', ['uses' => 'TourController@update']);
        $router->get('tours/{tour_id}/places', ['uses' => 'TourController@getPlaces']);

        $router->get('plays', ['uses' => 'PlayController@getPlay']);
        $router->post('plays', ['uses' => 'PlayController@createPlay']);
        $router->post('plays/{play_id}/places', ['uses' => 'PlayController@completePlace']);

        $router->post('support', ['uses' => 'UserController@postMessage']);

    });

});
