<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/logout', 'LoginController@logout');

Route::get('/signup', 'SignUpController@index');
Route::post('/signup', 'SignUpController@signup');

// authenticated routes
Route::middleware(['authenticated'])->group(function(){
  Route::get('/profile', 'UserController@index');
  Route::post('/profile', 'UserController@updateTeams');
  Route::post('/delete_teams', 'UserController@removeTeams');

  Route::get('/upcoming', 'UpcomingMatchesController@index');
});
