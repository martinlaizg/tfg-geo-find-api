<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
|	$router->get('URI', ['uses' => 'Controller@method'])
|	$router->post
|	$router->put
|	$router->delete
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
	$router->get(	'',				['uses' => 'HomeController@home']);

	$router->get(	'users',		['uses' => 'UserController@showAllusers']);
	$router->post(	'users',		['uses' => 'UserController@create']);
	$router->get(	'users/{id}',	['uses' => 'UserController@showOneUser']);
	$router->delete('users/{id}',	['uses' => 'UserController@delete']);
	$router->put(	'users/{id}',	['uses' => 'UserController@update']);

  });