<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::prefix('/countries')->group(function () {
        Route::get('/', 'CountriesController@index');
        Route::get('/all', 'CountriesController@fetchAll')->name('countries.index');
        Route::get('/fetch', 'CountriesController@fetch')->name('countries.fetch');
        Route::post('/add','CountriesController@create');
        Route::patch('/{country}/update', 'CountriesController@update');
    });

    Route::prefix('/customers')->group(function () {
        Route::get('/', 'CustomersController@index');
        Route::get('/all', 'CustomersController@fetchAll')->name('customers.index');
        Route::post('/add', 'CustomersController@create');
        Route::patch('/{customer}/update', 'CustomersController@update');
    });
});
