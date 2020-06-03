<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// ------------------------- PUBLIC
Route::group(['as' => 'public'], function () {
    // auth
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get('user', 'UserController@user');
    Route::get('users', 'UserController@index');

    // contract
    Route::get('contracts', 'ContractController@index');
    Route::get('contracts/{id}', 'ContractController@show');
    Route::post('contracts', 'ContractController@store');
    Route::put('contracts/{id}', 'ContractController@update');

    // projects
    Route::get('projects', 'ProjectController@index');
    Route::get('filtered-projects', 'ProjectController@getSearchedProjects');

    // customers
    Route::get('customers', 'CustomerController@getSearchedCustomers');

    // types
    Route::get('contract-types', 'TypeController@getContractTypes');

    // files
    Route::get('files/{id}', 'FileDocController@show');
    Route::get('files/{id}/download', 'FileDocController@download');
    Route::post('files/{id}', 'FileDocController@store');

    // people
    Route::get('people', 'PersonController@index');

});

