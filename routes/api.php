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
    Route::post('contracts', 'ContractController@create');
    Route::put('contracts/{id}', 'ContractController@update');

    // projects
    Route::get('projects', 'ProjectController@index');
    Route::get('filtered-projects', 'ProjectController@getSearchedProjects');
    // customers
    Route::get('customers', 'CustomerController@getSearchedCustomers');
    Route::get('customer/{id}/projects', 'ProjectController@getProjectsForCustomer');

    Route::get('contract-types', 'TypeController@getContractTypes');

    Route::get('file/{id}', 'FileController@show');
    Route::get('file/{id}/download', 'FileController@getDownload');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
