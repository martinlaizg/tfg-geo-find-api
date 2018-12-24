<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
 */

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('', ['uses' => 'HomeController@home']);
    $router->get('dashboard', ['uses' => 'HomeController@dashboard']);

    // User endpoints
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('', ['uses' => 'UserController@getAll']);
        $router->post('', ['uses' => 'UserController@create']);
        $router->get('{id}', ['uses' => 'UserController@get']);
        $router->put('{id}', ['uses' => 'UserController@update']);
        $router->delete('{id}', ['uses' => 'UserController@delete']);
    });

    // Map endpoints
    $router->group(['prefix' => 'maps'], function () use ($router) {
        $router->get('', ['uses' => 'MapController@getAll']);
        $router->post('', ['uses' => 'MapController@create']);
        $router->get('{id}', ['uses' => 'MapController@get']);
        $router->put('{id}', ['uses' => 'MapController@update']);
        $router->delete('{id}', ['uses' => 'MapController@delete']);
    });

    // Location endpoints
    $router->group(['prefix' => 'locations'], function () use ($router) {
        $router->get('', ['uses' => 'LocationController@getAll']);
        $router->post('', ['uses' => 'LocationController@create']);
        $router->get('{id}', ['uses' => 'LocationController@get']);
        $router->put('{id}', ['uses' => 'LocationController@update']);
        $router->delete('{id}', ['uses' => 'LocationController@delete']);
    });

    // Search endpoints
	$router->get('search', ['uses' => 'SearchController@search']);

	// Login
	$router->post('login', ['uses' => 'LoginController@login']);

	// Register
	$router->post('register', ['uses' => 'LoginController@register']);
});
