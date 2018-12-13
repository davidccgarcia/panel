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

Route::get('/', function () {
    return view('welcome');
});

Route::get('users', 'UserController@index')
    ->name('users');

Route::get('users/{id}', 'UserController@show')
    ->name('users.details')
    ->where('id', '[0-9]+');

Route::get('users/new', 'UserController@create');

Route::get('users/{id}/edit', 'UserController@edit')
    ->where('id', '[0-9]+');

Route::get('greet/nickname/{nickname}', 'WelcomeUserController@welcomeWithNickname');
Route::get('greet/{name}/', 'WelcomeUserController@welcomeWithName');
