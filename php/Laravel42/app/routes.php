<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', ['uses' => 'HomeController@showWelcome', 'as' => 'simple_route_name']);
Route::get('/distributed-tracing', 'HomeController@distributedTracing');
Route::get('/distributed-tracing-backend', 'HomeController@distributedTracingBackend');
Route::get('/action-that-exits', 'HomeController@actionThatExits');
