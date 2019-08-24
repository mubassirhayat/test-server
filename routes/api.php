<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['as' => 'api.', 'namespace' => 'APIs'], function ()
{
    Route::group(['prefix' => 'v1', 'as' => 'v1.'], function()
    {
		Route::post('register', 'RegisterAPIController@register')->name('register');
		Route::get('server-key', 'CredentialAPIController@index')->name('server-key.index');
		Route::get('secret/{key}', 'ClientSecretAPIController@show')->name('secret.show');
		Route::post('secret', 'ClientSecretAPIController@store')->name('secret.store');
    });
});
