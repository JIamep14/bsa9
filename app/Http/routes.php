<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(array('prefix' => 'api'), function()
{
    Route::post('book/{id}/return/{uid}', 'BookController@returnBook');
    Route::post('user/{id}/give/{bid}', 'UserController@giveBook');
    Route::get('user/{id}/books', 'UserController@userBooks');
    Route::resource('user', 'UserController');
    Route::resource('book', 'BookController');

});


Route::get('/', function() {
    return 'Use API';
});



