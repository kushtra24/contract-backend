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
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    Route::get('contracts', 'ContractController@index');
    Route::post('contracts', 'ContractController@store');
    Route::put('contracts/{id}', 'ContractController@update');

    // projects
    Route::get('projects', 'ProjectController@index');
    Route::get('filtered-projects', 'ProjectController@getSearchedProjects');

    // customers
    Route::get('customers', 'CustomerController@getSearchedCustomers');
    Route::get('customer/{id}/projects', 'ProjectController@getProjectsForCustomer');

    // types
    Route::get('contract-types', 'TypeController@getContractTypes');

    // files
    Route::get('file/{id}', 'FileDocController@show');
    Route::get('file/{id}/download', 'FileDocController@getDownload');
    Route::post('file/{id}', 'FileDocController@store');

    // people
    Route::get('people', 'PersonController@index');

});

