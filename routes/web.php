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

Route::middleware(['auth'])->group(function() {
    Route::get('/', function () {
        return view('home');
    });
    Route::prefix('/countries')->group(function() {
        Route::get('/', 'CountriesController@index');
        Route::get('/all', 'CountriesController@fetchAll')->name('countries.index');
        Route::get('/fetch', 'CountriesController@fetch')->name('countries.fetch');
        Route::post('/add','CountriesController@store');
        Route::patch('/{country}/update', 'CountriesController@update');
    });

    Route::prefix('/customers')->group(function() {
        Route::get('/', 'CustomersController@index');
        Route::get('/all', 'CustomersController@fetchAll')->name('customers.index');
        Route::get('/fetch', 'CustomersController@fetch')->name('customers.fetch');
        Route::post('/add', 'CustomersController@store');
        Route::prefix('/{customer}')->group(function() {
            Route::patch('/update', 'CustomersController@update');
            Route::get('/destinations', 'DestinationController@fetch')->name('destination.fetch');
            Route::post('/destinations/find', 'DestinationController@findOrNew')->name('destination.findOrNew');
        });
    });

    Route::prefix('/species')->group(function() {
        Route::get('/', 'SpeciesController@index');
        Route::get('/all', 'SpeciesController@fetchAll')->name('species.index');
        Route::get('/fetch', 'SpeciesController@fetch')->name('species.fetch');
        Route::post('/add', 'SpeciesController@store');
        Route::patch('/{species}/update', 'SpeciesController@update');
    });

    Route::prefix('/products')->group(function() {
        Route::get('/', 'ProductTypesController@index');
        Route::get('/all', 'ProductTypesController@fetchAll')->name('products.index');
        Route::get('/fetch', 'ProductTypesController@fetch')->name('products.fetch');
        Route::post('/add', 'ProductTypesController@store');
        Route::patch('/{product}/update', 'ProductTypesController@update');
    });
});
