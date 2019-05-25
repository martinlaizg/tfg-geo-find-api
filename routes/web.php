<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
 */

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('', ['uses' => 'HomeController@home']);

    $router->post('login', ['uses' => 'UserController@login']);
    $router->post('login/{provider}', ['uses' => 'UserController@loginProvider']);

    //
    // User requests
    $router->post('users', ['uses' => 'UserController@create']);
    $router->get('users/{user_id}', ['uses' => 'UserController@get']);

    //
    // Tour requests
    $router->get('tours', ['uses' => 'TourController@getAll']);
    $router->post('tours', ['uses' => 'TourController@create']);

    $router->get('tours/{tour_id}', ['uses' => 'TourController@getSingleTour']);
    $router->put('tours/{tour_id}', ['uses' => 'TourController@update']);

    $router->get('tours/{tour_id}/places', ['uses' => 'TourController@getPlaces']);
    $router->put('tours/{tour_id}/places', ['uses' => 'TourController@updatePlaces']);

    //
    // Play requests
    $router->get('plays', ['uses' => 'PlayController@getPlay']);
    $router->post('plays', ['uses' => 'PlayController@createPlay']);
    $router->post('plays/{play_id}/places', ['uses' => 'PlayController@completePlace']);

    //
    // Send message
    $router->post('support', ['uses' => 'UserController@postMessage']);

});
